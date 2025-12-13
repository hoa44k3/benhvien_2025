<?php

namespace App\Http\Controllers;

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

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->latest()->get();
        $departments = Department::where('status', 'active')->latest()->get();
        return view('site.home', compact('categories','departments'));
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
        return view('site.service_show', compact('service'));
    }

    public function schedule()
    {
        $departments = Department::where('status', 'active')->latest()->get();
        $doctors = DoctorSite::with('user', 'department')->where('status', 1)->latest()->get();
        $timeSlots = ['08:00', '08:30', '09:00', '09:30', '10:00', '14:00', '14:30', '15:00', '15:30'];

        return view('site.schedule', compact('departments','doctors', 'timeSlots'));
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
        // dd($data);

        Appointment::create($data);

        return redirect()->route('schedule')->with('success', 'Đặt lịch thành công!');

    } catch (\Throwable $e) {
        return back()->with('error', 'Lỗi: ' . $e->getMessage());
    }
}

    // public function medical_records()
    // {
    //     $user = Auth::user();
    //     if (!$user) return redirect()->route('login');

    //     $medicalRecords = MedicalRecord::where('user_id', $user->id)->orderBy('date', 'desc')->get();
    //     $prescriptions = Prescription::where('patient_id', $user->id)->latest()->get();

    //     return view('site.medical_records', compact('user', 'medicalRecords', 'prescriptions'));
    // }
    public function medical_records()
{
    $user = Auth::user();
    if (!$user) return redirect()->route('login');

    // 1. Lấy Hồ sơ bệnh án
    $medicalRecords = MedicalRecord::where('user_id', $user->id)
        ->with(['doctor', 'department']) // Load bác sĩ & khoa để hiển thị tên
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
    
    public function myAppointments() { return redirect()->route('schedule'); }
    public function payment() { return view('site.payment'); }
    public function contact() { return view('site.contact'); }
}