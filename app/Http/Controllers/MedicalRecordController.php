<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\TestResult;
use App\Models\MedicalRecordFile;
use App\Models\User;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\MedicalRecordCompletedMail;
use Illuminate\Support\Facades\Mail;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập.');
        }

        // Khởi tạo query
        $query = MedicalRecord::with(['doctor', 'department', 'user']);

        // --- 1. PHÂN QUYỀN: Nếu là Bác sĩ, chỉ thấy bệnh nhân của mình ---
       // Kiểm tra trực tiếp qua quan hệ roles
        if ($user->roles()->where('name', 'doctor')->exists()) {
            $query->where('doctor_id', $user->id);
        }
        // --- 2. BỘ LỌC ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhereHas('user', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // --- 3. SẮP XẾP ---
        // Quan trọng: Order by doctor_id trước để khi group ở View nó liền mạch
        $query->orderBy('doctor_id', 'asc');

        if (!$request->filled('sort_date')) {
            // Ưu tiên đang khám, sau đó mới nhất
            $query->orderByRaw("FIELD(status, 'đang_khám') DESC")
                  ->orderBy('id', 'desc');
        } else {
            $query->orderBy('date', $request->sort_date);
        }

        // --- 4. PHÂN TRANG ---
        $medicalRecords = $query->paginate(20); // Tăng lên 20 để nhìn bảng mỗi bác sĩ đầy đặn hơn

        $departments = Department::all();

        return view('medical_records.index', compact('medicalRecords', 'user', 'departments'));
    }
// ...
    /**
     * Hiển thị Lộ trình/Timeline điều trị của một bệnh nhân
     */
    public function patientTimeline($patientId)
    {
        // 1. Lấy thông tin bệnh nhân
        $patient = User::findOrFail($patientId);

        // 2. Lấy toàn bộ lịch sử khám, sắp xếp mới nhất lên đầu
        // Load kèm: Bác sĩ, Đơn thuốc, Kết quả xét nghiệm
        $history = MedicalRecord::where('user_id', $patientId)
            ->with(['doctor', 'prescriptions.items', 'testResults','files'])
            ->orderBy('date', 'desc')
            ->get();

        // 3. Tính toán thống kê nhanh (Optional)
        $totalVisits = $history->count();
        $lastVisit = $history->first() ? $history->first()->date : 'Chưa khám';

        return view('medical_records.timeline', compact('patient', 'history', 'totalVisits', 'lastVisit'));
    }
// ...
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all(); 
        $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
        $departments = Department::all();

        return view('medical_records.create', compact('users', 'doctors', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'doctor_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis' => 'nullable|string',
            'diagnosis_primary' => 'nullable|string|max:255',
            'diagnosis_secondary' => 'nullable|string|max:255',
            'treatment' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'vital_signs' => 'nullable|array', 
            'status' => 'required|in:chờ_khám,đang_khám,đã_khám,hủy',
            'next_checkup' => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->has('vital_signs')) {
            $data['vital_signs'] = json_encode($request->vital_signs);
        }

        MedicalRecord::create($data);

        return redirect()->route('medical_records.index')->with('success', 'Thêm hồ sơ bệnh án thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalRecord $medical_record)
    {
        $medical_record->load([
            'user', 
            'doctor', 
            'department', 
            'prescriptions.items', 
            'testResults', 
            'files'
        ]); 

        return view('medical_records.show', compact('medical_record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalRecord $medical_record)
    {
        $users = User::all(); 
        $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
        $departments = Department::all();

        return view('medical_records.edit', [
            'medical_record' => $medical_record,
            'users' => $users,
            'doctors' => $doctors,
            'departments' => $departments
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, MedicalRecord $medical_record)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'title' => 'required|string|max:255',
    //         'date' => 'required|date',
    //         'doctor_id' => 'nullable|exists:users,id',
    //         'department_id' => 'nullable|exists:departments,id',
    //         'appointment_id' => 'nullable|exists:appointments,id',
    //         'diagnosis' => 'nullable|string',
    //         'diagnosis_primary' => 'nullable|string|max:255',
    //         'diagnosis_secondary' => 'nullable|string|max:255',
    //         'treatment' => 'nullable|string',
    //         'symptoms' => 'nullable|string',
    //         'vital_signs' => 'nullable|array',
    //         'status' => 'required|in:chờ_khám,đang_khám,đã_khám,hủy',
    //         'next_checkup' => 'nullable|date',

    //         // Validate mảng vital_signs
    //         'vital_signs.bp' => 'nullable|string',
    //         'vital_signs.hr' => 'nullable|numeric',
    //         'vital_signs.temp' => 'nullable|numeric',
    //         'vital_signs.weight' => 'nullable|numeric',
    //         'vital_signs.spo2' => 'nullable|numeric',
    //     ]);

    //     $data = $request->all();

    //     if ($request->has('vital_signs')) {
    //         $data['vital_signs'] = json_encode($request->vital_signs);
    //     }

    //     $medical_record->update($data);

    //     return redirect()->route('medical_records.index')->with('success', 'Cập nhật hồ sơ bệnh án thành công.');
    // }
    /**
     * CẬP NHẬT HỒ SƠ (Xử lý cả 2 trường hợp: Admin sửa & Bác sĩ khám)
     */
    public function update(Request $request, MedicalRecord $medical_record)
    {
        // 1. KIỂM TRA LOẠI CẬP NHẬT
        // Nếu request có gửi 'title' -> Đây là Admin đang sửa thông tin hành chính
        if ($request->has('title')) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'date' => 'required|date',
                'status' => 'required',
                 'date' => 'required|date',
            'doctor_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis_primary' => 'nullable|string|max:255',
            'diagnosis_secondary' => 'nullable|string|max:255',
            'status' => 'required|in:chờ_khám,đang_khám,đã_khám,hủy',
            'next_checkup' => 'nullable|date',
                // Các trường khác nếu cần
            ]);
        } else {
            // Ngược lại -> Đây là Bác sĩ đang nhập kết quả khám (từ trang show)
            // Chỉ validate các trường chuyên môn
            $request->validate([
                'diagnosis' => 'nullable|string',
                'treatment' => 'nullable|string',
                'symptoms' => 'nullable|string',
                'vital_signs' => 'nullable|array',
                'vital_signs.bp' => 'nullable|string',
                'vital_signs.hr' => 'nullable|numeric',
                'vital_signs.temp' => 'nullable|numeric',
                'vital_signs.weight' => 'nullable|numeric',
                'vital_signs.spo2' => 'nullable|numeric',
            ]);
        }

        // 2. XỬ LÝ DỮ LIỆU
        // Loại bỏ vital_signs và files khỏi mảng data chính để xử lý riêng
        $data = $request->except(['vital_signs', 'files', '_token', '_method']);

        // Xử lý JSON Vital Signs (Chỉ số sinh tồn)
        if ($request->has('vital_signs')) {
            // Nếu có dữ liệu mới thì cập nhật, không thì giữ nguyên hoặc null
            $data['vital_signs'] = json_encode($request->vital_signs);
        }

        // 3. LƯU VÀO DATABASE
        $medical_record->update($data);

        return redirect()->back()->with('success', 'Đã lưu thông tin hồ sơ thành công.');
    }
    /**
     * [MỚI] Tải lên Minh chứng / Kết quả xét nghiệm (Upload Evidence)
     */
    public function uploadEvidence(Request $request, $id)
    {
        $request->validate([
            'files.*' => 'required|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // Max 10MB
        ]);

        $record = MedicalRecord::findOrFail($id);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // 1. Lưu file vào storage/app/public/medical_files
                $path = $file->store('medical_files', 'public');

                // 2. Lưu thông tin vào bảng medical_record_files
                MedicalRecordFile::create([
                    'medical_record_id' => $record->id,
                    'uploaded_by' => Auth::id(),
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'title' => 'Minh chứng khám bệnh',
                    'status' => 'active'
                ]);
            }
            return back()->with('success', 'Đã tải lên minh chứng thành công!');
        }

        return back()->with('error', 'Vui lòng chọn file để tải lên.');
    }
    /**
     * [MỚI] Xóa file minh chứng
     */
    public function deleteFile($fileId)
    {
        $file = MedicalRecordFile::findOrFail($fileId);
        
        // Xóa file vật lý
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        
        // Xóa trong DB
        $file->delete();

        return back()->with('success', 'Đã xóa file minh chứng.');
    }
public function startExam(MedicalRecord $medical_record)
{
    if ($medical_record->status == 'chờ_khám') {
        $medical_record->update(['status' => 'đang_khám']);
        return back()->with('success', 'Đã bắt đầu ca khám. Vui lòng nhập thông tin chẩn đoán.');
    }
    return back()->with('error', 'Trạng thái không hợp lệ để bắt đầu.');
}

public function cancel(MedicalRecord $medical_record)
{
    if ($medical_record->status != 'đã_khám') {
        $medical_record->update(['status' => 'hủy']);
        return back()->with('success', 'Đã hủy hồ sơ khám bệnh này.');
    }
    return back()->with('error', 'Không thể hủy hồ sơ đã hoàn tất.');
}
    /**
     * Hoàn tất khám bệnh và Tự động tạo hóa đơn
     */

/**
     * Hoàn tất khám bệnh và Tự động tạo hóa đơn
     * (PHIÊN BẢN MÔ HÌNH 2: CHỈ TÍNH PHÍ KHÁM, KHÔNG TÍNH THUỐC)
     */
    public function complete(MedicalRecord $medical_record)
    {
        if ($medical_record->status != 'đang_khám') {
            return back()->with('error', 'Hồ sơ này không ở trạng thái đang khám.');
        }

        try {
            DB::beginTransaction();

            // 1. Cập nhật trạng thái Hồ sơ -> Đã khám
            $medical_record->update(['status' => 'đã_khám']);

            // 2. Cập nhật trạng thái Lịch hẹn -> Completed (Để tính lương bác sĩ)
            if ($medical_record->appointment_id) {
                \App\Models\Appointment::where('id', $medical_record->appointment_id)
                    ->update(['status' => 'Hoàn thành']);
            }

            // 3. Tính toán tiền (CHỈ LẤY PHÍ KHÁM)
            // Lấy giá từ Khoa, nếu không có thì mặc định 200k
            $examFee = $medical_record->department->fee ?? 200000; 
            
            // --- BỎ PHẦN TÍNH TIỀN THUỐC ---
            // $medicineTotal = ... (Xóa đoạn này)
            
            // Cập nhật trạng thái đơn thuốc (nếu có) thành đã xong (để chốt đơn)
            $prescription = $medical_record->prescriptions()->latest()->first();
            if ($prescription) {
                // Đổi trạng thái thành 'Đã duyệt' hoặc 'Hoàn thành' thay vì 'Đã phát thuốc'
                // Vì mình không phát thuốc
                $prescription->update(['status' => 'Đã duyệt']);
            }

            $totalAmount = $examFee; // Tổng tiền chỉ bằng phí khám

            // 4. Tạo Hóa đơn (Chỉ thu phí dịch vụ)
            $invoice = Invoice::create([
                'code' => 'HD-' . strtoupper(Str::random(8)),
                'user_id' => $medical_record->user_id,
                'medical_record_id' => $medical_record->id,
                'appointment_id' => $medical_record->appointment_id,
                'prescription_id' => $prescription ? $prescription->id : null,
                'total' => $totalAmount,
                'status' => 'unpaid', // Để unpaid để lễ tân thu tiền hoặc check banking
                'created_by' => Auth::id(),
                'issued_date' => now(),
                'note' => 'Thu phí dịch vụ khám bệnh trực tuyến'
            ]);

            // 5. Lưu chi tiết hóa đơn (Chỉ 1 dòng duy nhất: Phí khám)
            DB::table('invoice_items')->insert([
                'invoice_id' => $invoice->id,
                'item_type' => 'service',
                'item_name' => 'Phí khám chuyên khoa ' . ($medical_record->department->name ?? 'Tổng quát'),
                'quantity' => 1,
                'price' => $examFee,
                'total' => $examFee,
                'created_at' => now(), 
                'updated_at' => now(),
            ]);

            // --- ĐÃ XÓA VÒNG LẶP LƯU THUỐC VÀO HÓA ĐƠN ---

            DB::commit();
// Gửi mail kết quả về cho bệnh nhân
        if ($medical_record->user && $medical_record->user->email) {
            Mail::to($medical_record->user->email)->send(new MedicalRecordCompletedMail($medical_record));
        }
            return redirect()->route('medical_records.index')
                            ->with('success', 'Hoàn tất khám bệnh! Đã tạo hóa đơn thu phí dịch vụ.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
// Thêm hàm này vào trong class MedicalRecord
    public function review()
    {
        return $this->hasOne(Review::class, 'medical_record_id');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalRecord $medical_record)
    {
        try {
            $medical_record->delete();
            return back()->with('success', 'Xóa hồ sơ thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi xóa hồ sơ: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $record = MedicalRecord::with(['user', 'doctor', 'testResults', 'prescriptions'])->findOrFail($id);
        return response()->json([
            'message' => 'Tính năng tải PDF đang được phát triển',
            'record' => $record,
        ]);
    }
}