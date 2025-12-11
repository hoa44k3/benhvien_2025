<?php

namespace App\Http\Controllers;
use App\Models\Service;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Department;
use App\Models\DoctorSite;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
         $categories = Category::where('status', 1)->latest()->get();
         // Lấy danh sách chuyên khoa (hoạt động)
        $departments = Department::where('status', 'active')->latest()->get();
        return view('site.home', compact('categories','departments'));
    }


public function services(Request $request)
{
    $categories = Category::where('status', 1)->latest()->get();

    $servicesQuery = Service::with(['category', 'department'])
        ->where('status', 1)
        ->latest();

    if ($request->has('category') && $request->category != 'all') {
        $servicesQuery->where('category_id', $request->category);
    }

    $services = $servicesQuery->get();

    $departments = Department::where('status', 'active')->latest()->get();

    $doctorsQuery = DoctorSite::with('user', 'department')
        ->where('status', 1)
        ->latest();

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
    // 1. Lấy danh sách chuyên khoa
    $departments = Department::where('status', 'active')->latest()->get();
 $doctors = DoctorSite::with('user', 'department')
                ->where('status', 1)
                ->latest()
                ->get();
    // 2. Sinh danh sách giờ khám (không cần DB)
    $timeSlots = array_merge(
        $this->generateTimeSlots("08:30", "11:00", 30),   // buổi sáng
        $this->generateTimeSlots("14:00", "17:00", 30)    // buổi chiều
    );

    // 3. Trả về view kèm dữ liệu
    return view('site.schedule', compact('departments','doctors', 'timeSlots'));
}
public function storeFromSite(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để đặt lịch.');
    }

    $request->validate([
        'department_id' => 'required|exists:departments,id',
        'doctor_id' => 'required|exists:doctor_sites,id', 
        'date' => 'required|date|after_or_equal:today',
        'time' => 'required',
        'patient_name' => 'required|string|max:255',
        'patient_phone' => 'required|string|max:20',
        'reason' => 'nullable|string|max:500',
    ]);

    // Lấy record doctor_site kèm user
    $doctorSite = \App\Models\DoctorSite::with('user')->find($request->doctor_id);
    if (!$doctorSite) {
        return back()->with('error', 'Bác sĩ không hợp lệ.');
    }

    // Tạo lịch hẹn
    Appointment::create([
        'code' => 'LH' . strtoupper(uniqid()),
        'user_id' => Auth::id(),                    // bệnh nhân
        'doctor_id' => $doctorSite->user_id,       // lưu User.id của bác sĩ
        'department_id' => $request->department_id,
        'patient_name' => $request->patient_name,
        'patient_phone' => $request->patient_phone,
        'reason' => $request->reason,
        'date' => $request->date,
        'time' => $request->time,
        'status' => 'Đang chờ',
        'approved_by' => null,
        'checked_in_by' => null,
    ]);

    return back()->with('success', 'Đặt lịch khám thành công!');
}

// Hàm sinh khung giờ khám
private function generateTimeSlots(string $start, string $end, int $minutes = 30): array
{
    $slots = [];
    $current = strtotime($start);
    $endTs = strtotime($end);

    while ($current <= $endTs) {
        $slots[] = date("H:i", $current);
        $current = strtotime("+{$minutes} minutes", $current);
    }

    return $slots;
}


    public function medical_records()
{
    $user = Auth::user(); // <-- thêm dòng này

    if (!$user) {
        return redirect()->route('login');
    }

    
    $medicalRecords = MedicalRecord::with([
        'doctor',
        'department',
        'prescriptions.items',
    ])
    ->where('user_id', $user->id)
    ->orderBy('date', 'desc')
    ->get();


    // Lấy toàn bộ đơn thuốc của bệnh nhân
    $prescriptions = Prescription::with(['doctor', 'items'])
        ->where('patient_id', $user->id)
        ->latest()
        ->get();

    // Trả ra view
    return view('site.medical_records', compact(
        'user',
        'medicalRecords',
        'prescriptions'
    ));
}

     public function payment()
    {
       
        return view('site.payment');
    }
    public function contact()
    {
       
        return view('site.contact');
    }

}
