<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Department;
use App\Models\DoctorSite;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Support\Facades\Mail;
use App\Models\Invoice;
use App\Mail\InvoicePaid;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Comment;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->latest()->get();
        $departments = Department::where('status', 'active')->latest()->get();

        // 1. Lấy bài viết nổi bật (Để hiển thị cột bên phải)
    $featuredPosts = Post::where('status', 'published')
                         ->where('is_featured', true)
                         ->latest()
                         ->take(3)
                         ->get();

    // 2. Lấy bài viết mới nhất (Hiển thị cột bên trái)
    // Loại trừ các bài đã nằm trong top nổi bật để tránh trùng lặp (tuỳ chọn)
    $latestPosts = Post::where('status', 'published')
                       ->whereNotIn('id', $featuredPosts->pluck('id')) 
                       ->latest()
                       ->take(4)
                       ->get();
        return view('site.home', compact('categories','departments', 'featuredPosts', 'latestPosts'));
    }

public function showPost($id)
{
    $post = Post::with(['author'])
        ->where('status', 'published')
        ->findOrFail($id);
    
    // Tăng view
    $post->increment('views');
    
    $comments = Comment::where('post_id', $id)
        ->whereNull('parent_id')
        ->where('status', 'approved') // Chỉ lấy đã duyệt
        ->where('is_visible', true)   // Chỉ lấy đang hiện
    ->with(['replies' => function($q) {
        $q->where('status', 'approved')
          ->where('is_visible', true)
          ->with(['replies' => function($q2) { // Load thêm cấp con nữa
              $q2->where('status', 'approved')->where('is_visible', true);
          }]);
    }])
        ->orderBy('created_at', 'desc')
        ->get();

    // Gán comments vào post để view dùng $post->comments như cũ (hoặc truyền biến riêng)
    $post->setRelation('comments', $comments);

    $relatedPosts = Post::where('id', '!=', $id)
                        ->inRandomOrder()->take(3)->get();

    return view('site.postshow', compact('post', 'relatedPosts'));
}

    public function storeReview(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'doctor_id' => 'required|exists:doctor_sites,user_id', // Chú ý: doctor_sites dùng user_id hay id làm khóa ngoại
            'medical_record_id' => 'required|exists:medical_records,id', // Gắn đánh giá vào đúng ca khám
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        // 2. Lưu đánh giá vào bảng reviews
        // Giả sử bạn có model Review
        \App\Models\Review::updateOrCreate([
            'user_id' => auth()->id(),
            'doctor_id' => $request->doctor_id,
            'medical_record_id' => $request->medical_record_id, // Quan trọng: Để biết ca này đánh giá chưa
            'rating' => $request->rating,
            'comment' => $request->comment,
            'created_at' => now()
        ]);

        // 3. TÍNH TOÁN LẠI SỐ SAO CHO BÁC SĨ (Logic Trung Bình Cộng)
        $doctorSite = \App\Models\DoctorSite::where('user_id', $request->doctor_id)->first();
        
        if ($doctorSite) {
            // Cách 1: Tính toán thủ công (nhanh)
            $currentRating = $doctorSite->rating;
            $currentCount = $doctorSite->reviews_count;
            
            $newCount = $currentCount + 1;
            $newRating = (($currentRating * $currentCount) + $request->rating) / $newCount;

            // Cách 2: (Chính xác nhất) Query lại toàn bộ bảng reviews để tính trung bình
            // $newRating = \App\Models\Review::where('doctor_id', $request->doctor_id)->avg('rating');
            // $newCount = \App\Models\Review::where('doctor_id', $request->doctor_id)->count();

            // Cập nhật vào bảng bác sĩ
            $doctorSite->update([
                'rating' => round($newRating, 1), // Làm tròn 1 số thập phân (VD: 4.5)
                'reviews_count' => $newCount
            ]);
        }

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá!');
    }
    public function storeComment(Request $request, $postId)
{
    // 1. Định nghĩa luật kiểm tra cơ bản
    $rules = [
        'content' => 'required|string|max:1000',
        'parent_id' => 'nullable|exists:comments,id'
    ];

    // 2. 🔥 QUAN TRỌNG: Chỉ bắt buộc nhập Tên/Email nếu CHƯA ĐĂNG NHẬP
    if (!Auth::check()) {
        $rules['name'] = 'required|string|max:50';
        $rules['email'] = 'nullable|email|max:100';
    }

    // Thực hiện Validate
    $request->validate($rules);

    // 3. Chuẩn bị dữ liệu
    $data = [
        'post_id' => $postId,
        'parent_id' => $request->parent_id,
        'content' => $request->content,
        'status' => 'pending', 
        'is_visible' => false, 
    ];

    // 4. Xử lý thông tin người dùng
    if (Auth::check()) {
        // Nếu đã đăng nhập -> Lấy thông tin từ Auth
        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['name'] = $user->name; // Tự điền tên từ tài khoản
        $data['email'] = $user->email;

        // (Tùy chọn) Admin bình luận thì duyệt luôn
        if ($user->role == 'admin') { // Hoặc check $user->usertype tùy code bạn
            $data['status'] = 'approved';
            $data['is_visible'] = true;
        }
    } else {
        // Nếu là khách -> Lấy thông tin từ Form
        $data['user_id'] = null;
        $data['name'] = $request->name;
        $data['email'] = $request->email;
    }

    // 5. Lưu vào database
    Comment::create($data);

    return back()->with('success', 'Bình luận đã gửi và đang chờ Admin duyệt!');
}
    public function services(Request $request)
    {
        $categories = Category::where('status', 1)->latest()->get();
        $departments = Department::where('status', 'active')->latest()->get();

        $servicesQuery = Service::with(['category', 'department'])->where('status', 1)->latest();
        if ($request->has('category') && $request->category != 'all') {
            $servicesQuery->where('category_id', $request->category);
        }
        $services = $servicesQuery->get();

        $doctorsQuery = DoctorSite::with('user', 'department')->where('status', 1)->latest();
        if ($request->has('department') && $request->department != 'all') {
            $doctorsQuery->where('department_id', $request->department);
        } else {
            $doctorsQuery->limit(3);
        }
        $doctors = $doctorsQuery->get();

        return view('site.services', compact('services', 'categories', 'departments', 'doctors'));
    }

   public function serviceShow(Service $service)
{
    // Lấy thêm các bác sĩ thuộc khoa của dịch vụ này để gợi ý (cho sát thực tế)
    $relatedDoctors = \App\Models\DoctorSite::where('department_id', $service->department_id)
        ->with('user')
        ->take(4)
        ->get();

    return view('site.service_show', compact('service', 'relatedDoctors'));
}
    public function schedule(Request $request)
    {
        // 1. Lấy danh sách Khoa (active)
        $departments = Department::where('status', 'active')->latest()->get();

        // 2. Lấy TẤT CẢ bác sĩ (status=1) kèm user và department
        // Ta lấy hết để JS ở Client tự lọc (ẩn/hiện) -> Trải nghiệm mượt hơn load lại trang
        $doctors = DoctorSite::with('user', 'department')
            ->where('status', 1)
            ->latest()
            ->get();

        // 3. Khung giờ khám
        $timeSlots = ['08:00', '08:30', '09:00', '09:30', '10:00', '14:00', '14:30', '15:00', '15:30'];

        // 4. Kiểm tra xem có khoa nào được chọn trước không (từ trang Dịch vụ chuyển sang)
        $selectedDeptId = $request->input('department_id');

        return view('site.schedule', compact('departments', 'doctors', 'timeSlots', 'selectedDeptId'));
    }

    // --- HÀM XỬ LÝ ĐẶT LỊCH (Tên chuẩn: storeFromSite) ---
    public function storeFromSite(Request $request)
    {
        // ... (Giữ nguyên kiểm tra đăng nhập)

        // 1. CHỈ VALIDATE NHỮNG CÁI NGƯỜI DÙNG CHẮC CHẮN CHỌN
        // (Bỏ department_id khỏi required, ta sẽ tự tìm nó)
        $request->validate([
            'doctor_id' => 'required', // Chỉ cần có bác sĩ
            'date' => 'required',
            'time' => 'required',
            'patient_name' => 'required',
            'patient_phone' => 'required',
        ]);

        try {
            $user = Auth::user();

            // 2. TỰ TÌM BÁC SĨ VÀ KHOA
            $doctorSite = DoctorSite::with('user')->find($request->doctor_id);
            if (!$doctorSite) return back()->with('error', 'Bác sĩ không tồn tại');

            // 🔥 LOGIC THÔNG MINH:
            // Nếu form không gửi department_id (do lỗi JS), ta lấy từ Bác sĩ luôn
            $deptId = $request->department_id;
            if (!$deptId) {
                $deptId = $doctorSite->department_id; // Tự động lấy ID khoa của bác sĩ
            }

            // Tạo mã bệnh nhân
            $patientCode = 'BN' . str_pad($user->id, 5, '0', STR_PAD_LEFT);

            // 3. Chuẩn bị dữ liệu
            $data = [
                'code' => 'LH' . strtoupper(uniqid()),
                'user_id' => $user->id,
                'doctor_id' => $doctorSite->user->id,
                
                'department_id' => $deptId, // Dùng ID khoa đã tự tìm được
                
                'patient_code' => $patientCode,
                'patient_name' => $request->patient_name,
                'patient_phone' => $request->patient_phone,
                'reason' => $request->reason,
                'date' => $request->date,
                'time' => $request->time,
                'status' => 'Đang chờ',
                'diagnosis' => null,
                'notes' => null,
                'approved_by' => null,
                'checked_in_by' => null,
            ];

            // 4. In ra dữ liệu để kiểm tra lần cuối (Xóa sau khi chạy OK)

            Appointment::create($data);

            return redirect()->route('schedule')->with('success', 'Đặt lịch thành công!');

        } catch (\Throwable $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }

    }
    public function search(Request $request)
        {
            $keyword = $request->input('keyword');

            // Tìm Bác sĩ (theo tên user hoặc chuyên khoa)
            $doctors = DoctorSite::whereHas('user', function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            })->orWhere('specialization', 'like', "%{$keyword}%")
            ->where('status', 1)
            ->with('user', 'department')
            ->get();

            // Tìm Khoa
            $departments = Department::where('name', 'like', "%{$keyword}%")
                                    ->where('status', 'active')
                                    ->get();

            // Tìm Dịch vụ
            $services = Service::where('name', 'like', "%{$keyword}%")
                            ->where('status', 1)
                            ->get();

            return view('site.search_results', compact('keyword', 'doctors', 'departments', 'services'));
        }
    
   // --- CHATBOT AI (HYBRID MODE: ONLINE AI + OFFLINE BACKUP) ---
   public function askBot(Request $request)
    {
        $userQuestion = $request->input('message');

        // 1. LẤY DỮ LIỆU BỆNH VIỆN
        $services = Service::where('status', 1)->take(15)->get()->map(function ($s) {
            return "{$s->name} ({$s->price}đ)";
        })->implode(', ');

        $doctors = DoctorSite::with('user', 'department')->where('status', 1)->take(10)->get()->map(function ($d) {
            return "BS.{$d->user->name}-{$d->department->name}";
        })->implode(', ');

        // 2. TẠO "NHÂN CÁCH" CHO AI
        $systemContext = "
            VAI TRÒ: Bạn là 'Trợ lý ảo SmartHospital' - một nhân viên y tế thân thiện, thông minh và thấu hiểu.
            
            NHIỆM VỤ:
            1. Trò chuyện tự nhiên, vui vẻ với người dùng về MỌI CHỦ ĐỀ (sức khỏe, đời sống, chào hỏi...).
            2. Nếu người dùng hỏi về Bệnh viện, hãy dùng dữ liệu sau để trả lời chính xác:
               - Dịch vụ hiện có: $services
               - Bác sĩ tiêu biểu: $doctors
               - Địa chỉ: 123 Lê Mao, TP Vinh. Hotline: 1900-1234.
            
            NGUYÊN TẮC TRẢ LỜI:
            - Giọng văn: Gần gũi, dùng từ ngữ đời thường (dạ, vâng, ạ, nhé...), không máy móc.
            - Nếu khách hỏi về bệnh tật/triệu chứng: Hãy tư vấn dựa trên kiến thức y khoa tổng quát, đưa ra lời khuyên hữu ích, nhưng LUÔN nhắc khách đi khám.
            - Không bao giờ nói 'Tôi không có thông tin'. Hãy dùng kiến thức xã hội để trả lời.
            
            Hãy trả lời câu hỏi sau của khách một cách ngắn gọn (dưới 150 từ):
        ";

        try {
            $apiKey = env('GEMINI_API_KEY');

            // --- SỬA LỖI Ở ĐÂY: Truyền đủ 3 tham số ---
            if (!$apiKey) return $this->offlineFallback($userQuestion, $services, $doctors);

            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

            $response = Http::withoutVerifying()
                ->timeout(5) // Timeout 5s
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($apiUrl, [
                    'contents' => [['parts' => [['text' => $systemContext . "\n\nKhách hỏi: " . $userQuestion]]]]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

                if ($reply) {
                    $cleanReply = str_replace(['**', '*'], '', $reply);
                    return response()->json(['reply' => nl2br($cleanReply)]);
                }
            }

            throw new \Exception('API Error');
        } catch (\Exception $e) {
            // --- SỬA LỖI Ở ĐÂY: Truyền đủ 3 tham số ---
            return $this->offlineFallback($userQuestion, $services, $doctors);
        }
    }

    // --- HÀM PHỤ TRỢ: TRẢ LỜI OFFLINE (KHI AI LỖI) ---
    private function offlineFallback($question, $services, $doctors)
    {
       $msg = mb_strtolower($question, 'UTF-8');
        $reply = "Hiện tại kết nối AI đang gián đoạn, nhưng em vẫn ở đây ạ! ";

        if (str_contains($msg, 'chào') || str_contains($msg, 'hi')) {
            $reply = "Chào bạn! SmartHospital có thể giúp gì cho bạn ạ? (Hệ thống đang hoạt động chế độ hỗ trợ nhanh)";
        } elseif (str_contains($msg, 'giá') || str_contains($msg, 'phí') || str_contains($msg, 'tiền') || str_contains($msg, 'dịch vụ')) {
            $reply = "Dạ đây là một số dịch vụ bên em:<br>" . nl2br($services) . "<br>Bạn cần tư vấn kỹ hơn vui lòng gọi 1900-1234 nhé!";
        } elseif (str_contains($msg, 'bác sĩ') || str_contains($msg, 'khám') || str_contains($msg, 'lịch')) {
            $reply = "Đội ngũ bác sĩ bên em gồm:<br>" . nl2br($doctors) . "<br>Bạn vào mục <b>'Đặt lịch khám'</b> để chọn bác sĩ nhé.";
        } elseif (str_contains($msg, 'địa chỉ') || str_contains($msg, 'ở đâu')) {
            $reply = "Bệnh viện SmartHospital nằm tại: 123 Đường Lê Mao, TP Vinh, Nghệ An ạ.";
        } elseif (str_contains($msg, 'giờ') || str_contains($msg, 'làm việc')) {
            $reply = "Bên em làm việc từ 7:00 - 17:00 (Thứ 2 đến Thứ 7). Cấp cứu trực 24/7 ạ.";
        } else {
            $reply = "Câu hỏi này cần chuyên viên tư vấn. Vui lòng gọi hotline 1900-1234 để được hỗ trợ ngay lập tức ạ!";
        }

        return response()->json(['reply' => $reply]);
    }

    // Hàm làm sạch văn bản AI trả về (bỏ dấu **)
    private function cleanText($text)
    {
        return str_replace(['**', '*'], '', $text);
    }

    // --- 3. ĐÁNH GIÁ BÁC SĨ (Review) ---
    public function medical_records()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        // 1. Lấy Hồ sơ bệnh án
        $medicalRecords = MedicalRecord::where('user_id', $user->id)
            ->with(['doctor', 'department','review']) // Load bác sĩ & khoa để hiển thị tên
            ->orderBy('date', 'desc')
            ->get();

        // 2. Lấy Đơn thuốc (Load thêm items để hiện chi tiết thuốc)
        $prescriptions = Prescription::where('patient_id', $user->id)
            ->with(['doctor', 'items']) 
            ->latest()
            ->get();

        // 3. Lấy Kết quả xét nghiệm (Mới thêm)
        $testResults = \App\Models\TestResult::where('user_id', $user->id)
            ->with(['doctor', 'department'])
            ->latest()
            ->get();

        return view('site.medical_records', compact('user', 'medicalRecords', 'prescriptions', 'testResults'));
    }
    /**
     * Hiển thị trang thanh toán của người dùng
     */
    public function payment()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        // Lấy danh sách hóa đơn của user đăng nhập
        $invoices = Invoice::where('user_id', $user->id)
            ->with(['items', 'medicalRecord', 'doctor']) // doctor lấy qua relation trong Invoice (nếu có) hoặc medicalRecord
            ->orderBy('created_at', 'desc')
            ->get();

        // Tính toán thống kê
        $unpaidTotal = $invoices->where('status', 'unpaid')->sum('total');
        $paidTotal = $invoices->where('status', 'paid')->sum('total');
        $totalAmount = $invoices->sum('total');

        return view('site.payment', compact('invoices', 'unpaidTotal', 'paidTotal', 'totalAmount'));
    }

    /**
     * Xử lý thanh toán từ người dùng
     */
    public function processPayment(Request $request, $id)
    {
        $invoice = Invoice::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($invoice->status == 'paid') {
            return back()->with('error', 'Hóa đơn này đã được thanh toán rồi.');
        }

        // Validate phương thức thanh toán
        $request->validate([
            // 'payment_method' => 'required|in:vnpay,momo,card,transfer',
            // Chỉ chấp nhận các từ có trong DB cũ
    'payment_method' => 'required|in:vnpay,momo,bank,cash',
        ]);

        // LOGIC THANH TOÁN (Giả lập)
        // Nếu dùng VNPAY/MOMO thật thì đoạn này sẽ redirect sang cổng thanh toán.
        // Ở đây ta giả lập thanh toán thành công ngay lập tức.
        
        $invoice->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'paid_at' => now(),
        ]);

        // Gửi email xác nhận
        try {
            Mail::to(Auth::user()->email)->send(new InvoicePaid($invoice));
        } catch (\Exception $e) {
            // Log lỗi mail nhưng không chặn quy trình
            Log::error('Lỗi gửi mail hóa đơn: ' . $e->getMessage());
        }

        return back()->with('success', 'Thanh toán thành công! Hóa đơn đã được gửi về email.');
    }
public function downloadInvoice($id)
    {
        // Kiểm tra đúng chủ sở hữu hóa đơn mới cho tải
        $invoice = Invoice::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['user', 'items'])
            ->firstOrFail();

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('HoaDon-'.$invoice->code.'.pdf');
    }
    public function myAppointments() { return redirect()->route('schedule'); }


    public function contact()
{
    // Lấy danh sách câu hỏi thường gặp active, sắp xếp theo thứ tự
    $faqs = Faq::where('is_active', true)->orderBy('order')->get();
    // 2. Lấy Lịch sử liên hệ (Mới thêm)
    $myContacts = collect(); // Mặc định là rỗng
    if (Auth::check()) {
        $user = Auth::user();
        // Lấy 5 tin nhắn gần nhất để hiển thị cho gọn
        $myContacts = \App\Models\Contact::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->take(5) 
            ->get();
    }
    return view('site.contact', compact('faqs', 'myContacts'));
}

public function sendContact(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|max:1000',
    ]);

    Contact::create($request->all());

    return back()->with('success', 'Tin nhắn của bạn đã được gửi. Chúng tôi sẽ phản hồi sớm nhất!');
}
public function myContactHistory()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        // Lấy các tin nhắn có email trùng với email tài khoản
        $contacts = Contact::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('site.my_contacts', compact('contacts'));
    }
}