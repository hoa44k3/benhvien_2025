<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\DoctorAttendance;
use App\Models\DoctorSite;
use App\Models\Shift;
use Carbon\Carbon;

class DoctorScheduleController extends Controller
{
public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $today = Carbon::now()->format('Y-m-d');

        // 1. Lấy Hồ sơ bác sĩ (để lấy thông tin lương, ngân hàng)
        $doctorProfile = DoctorSite::where('user_id', $user->id)->first();

        // 2. Lấy thông tin Chấm công HÔM NAY (để hiện nút Check-in/Check-out)
        $todayAttendance = DoctorAttendance::where('doctor_id', $user->id)
                            ->where('date', $today)
                            ->first();

        // 3. Lấy Lịch khám hôm nay
        $appointments = Appointment::where('doctor_id', $user->id)
            ->whereDate('date', $today)
            ->orderBy('time', 'asc')
            ->get();

        // 4. Lấy Ca làm việc (Shifts) --> ĐÃ THÊM LẠI ĐỂ SỬA LỖI
        $shifts = Shift::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->get();

        // 5. TÍNH LƯƠNG TẠM TÍNH (Real-time)
        $salaryStats = $this->calculateMonthlySalary($user->id, $doctorProfile);

        // Truyền đủ biến vào View
        return view('doctor.schedule.index', compact(
            'user', 
            'doctorProfile', 
            'todayAttendance', 
            'appointments', 
            'salaryStats',
            'today',
            'shifts' // <--- Đã có biến này, hết lỗi compact
        ));
    }
// Hàm phụ trợ tính lương
    private function calculateMonthlySalary($userId, $profile)
    {
        // 1. Nếu chưa có hồ sơ bác sĩ -> Trả về tất cả bằng 0 để tránh lỗi View
        if (!$profile) {
            return [
                'work_days' => 0,
                'base_salary' => 0,   // <--- Quan trọng: Phải có key này
                'commission' => 0,
                'total' => 0
            ];
        }

        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = \Carbon\Carbon::now()->year;
        $standardDays = 26; // Quy chuẩn 26 công/tháng

        // 2. Tính số công thực tế
        $actualWorkDays = DoctorAttendance::where('doctor_id', $userId)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->whereIn('status', ['present', 'late'])
            ->count();

        // 3. Tính lương cứng (Nếu DB chưa có cột base_salary thì mặc định là 0)
        $baseSalary = $profile->base_salary ?? 0; 
        $baseSalaryReceived = ($baseSalary / $standardDays) * $actualWorkDays;

        // 4. Tính hoa hồng (Demo logic)
        $completedApps = Appointment::where('doctor_id', $userId)
            ->where('status', 'Hoàn thành')
            ->whereMonth('date', $currentMonth)
            ->count();
        
        $examFee = 200000; 
        $commissionRate = $profile->commission_exam_percent ?? 0;
        $totalExamRevenue = $completedApps * $examFee;
        $commissionReceived = $totalExamRevenue * ($commissionRate / 100);

        // 5. Trả về mảng kết quả
        return [
            'work_days' => $actualWorkDays,
            'base_salary' => $baseSalaryReceived, // <--- Key này phải khớp với View gọi
            'commission' => $commissionReceived,
            'total' => $baseSalaryReceived + $commissionReceived
        ];
    }
    
    /**
     * Form thêm lịch khám mới
     */
    public function create()
    {
        return view('doctor.schedule.create');
    }

    /**
     * Lưu lịch khám mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'room' => 'nullable|string|max:50',
            'status' => 'required|in:Đang chờ,Đang khám,Hoàn thành,Hủy hẹn',
            'priority' => 'nullable|in:Thấp,Trung bình,Cao',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();

       Appointment::create([
    'code' => 'LH' . strtoupper(uniqid()), // 🔹 Tạo mã lịch hẹn duy nhất
    'doctor_id' => $user->id,
    'patient_name' => $validated['patient_name'],
    'date' => $validated['date'],
    'time' => $validated['time'],
    'room' => $validated['room'] ?? null,
    'status' => $validated['status'],
    'priority' => $validated['priority'] ?? 'Thấp',
    'notes' => $validated['notes'] ?? null,
]);


        return redirect()->route('doctor.schedule.index')
            ->with('success', '✅ Thêm lịch khám thành công!');
    }

    /**
     * Cập nhật trạng thái lịch hẹn
     */
    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:Đang chờ,Đang khám,Hoàn thành,Hủy hẹn',
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

        return back()->with('success', '✅ Cập nhật trạng thái thành công!');
    }

    /**
     * Cập nhật ca làm việc
     */
    public function updateShift(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'shift' => 'required|in:Sáng,Chiều,Nghỉ',
            'room' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();

        Shift::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $validated['date'],
            ],
            [
                'shift' => $validated['shift'],
                'room' => $validated['room'] ?? null,
            ]
        );

        return back()->with('success', '✅ Cập nhật ca làm việc thành công!');
    }
   
}
