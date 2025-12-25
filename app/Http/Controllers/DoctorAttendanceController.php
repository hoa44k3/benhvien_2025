<?php

namespace App\Http\Controllers;
use App\Models\DoctorAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DoctorAttendanceController extends Controller
{
    // Không cần cấu hình giờ cứng START_TIME/END_TIME nữa vì bác sĩ online linh động
    
    public function index(Request $request)
    {
        $doctorId = $request->doctor_id;
        $user = Auth::user();

        // 1. Phân quyền xem
        if ($user->roles->contains('name', 'doctor') || $user->type == 'doctor') {
            $doctorId = $user->id;
        }

        $month = $request->month ?? date('Y-m');

        // 2. Query dữ liệu
        $query = DoctorAttendance::with('user')
            ->whereMonth('date', Carbon::parse($month)->month)
            ->whereYear('date', Carbon::parse($month)->year)
            // Lọc bác sĩ
            ->whereHas('user', function($q) {
                $q->whereHas('roles', function($r) {
                    $r->where('name', 'doctor');
                })->orWhere('type', 'doctor');
            })
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc');

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $attendances = $query->get();

        // 3. Danh sách bác sĩ cho Admin lọc
        $doctors = User::whereHas('roles', function($q) {
            $q->where('name', 'doctor');
        })->orWhere('type', 'doctor')->get();

        return view('doctor_attendances.index', compact(
            'attendances',
            'doctorId',
            'month',
            'doctors'
        ));
    }

    /**
     * BẮT ĐẦU CA TRỰC (Online)
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now('Asia/Ho_Chi_Minh'); 
        $today = $now->format('Y-m-d');

        // Kiểm tra xem ca này trong ngày đã check-in chưa (Cho phép 1 ngày làm nhiều ca)
        // Logic: Nếu có bản ghi nào của hôm nay mà CHƯA check-out thì không cho tạo mới
        $activeShift = DoctorAttendance::where('doctor_id', $user->id)
            ->where('date', $today)
            ->whereNull('check_out')
            ->first();

        if ($activeShift) {
            return back()->with('error', 'Bạn đang trong một ca trực rồi. Vui lòng kết thúc ca trước!');
        }

        // Tạo ca mới
        DoctorAttendance::create([
            'doctor_id' => $user->id,
            'date'      => $today,
            'check_in'  => $now->format('H:i:s'),
            'status'    => 'active', // Trạng thái: Đang hoạt động
            'shift'     => $request->shift, // Ca Sáng/Chiều/Tối
            'note'      => $request->note
        ]);

        return back()->with('success', 'Đã bắt đầu ca trực! Hệ thống đã bật trạng thái Sẵn sàng nhận bệnh nhân.');
    }

    /**
     * KẾT THÚC CA TRỰC (Offline)
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        
        // Tìm ca đang hoạt động (chưa check-out)
        $attendance = DoctorAttendance::where('doctor_id', $user->id)
                        ->whereNull('check_out')
                        ->latest('created_at')
                        ->first();

        if (!$attendance) {
            return back()->with('error', 'Bạn hiện không có ca trực nào để kết thúc.');
        }

        // Tính giờ làm
        $checkInTime = Carbon::parse($attendance->date . ' ' . $attendance->check_in);
        $totalMinutes = abs($now->diffInMinutes($checkInTime));
        $totalHours = $totalMinutes / 60;

        $attendance->update([
            'check_out'   => $now->format('H:i:s'),
            'total_hours' => round($totalHours, 2),
            'status'      => 'completed', // Đã hoàn thành ca
            'note'        => $attendance->note . ($request->note ? ' | ' . $request->note : '')
        ]);

        return back()->with('success', 'Đã kết thúc ca trực. Tổng thời gian online: ' . round($totalHours, 2) . ' giờ.');
    }
}