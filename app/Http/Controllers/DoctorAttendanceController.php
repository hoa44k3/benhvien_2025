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
    // Cấu hình giờ làm việc
    const START_TIME = '08:00:00'; // Giờ bắt đầu
    const END_TIME   = '17:00:00'; // Giờ được phép về
     // Danh sách chấm công
    public function index(Request $request)
    {
        // Nếu là Admin thì xem được chọn bác sĩ, nếu là bác sĩ thì chỉ xem của mình
        $doctorId = $request->doctor_id;
        
        // Nếu người dùng hiện tại là bác sĩ (không phải admin), bắt buộc xem của chính mình
        if (Auth::user()->type == 'doctor') { // Hoặc check role tương ứng
            $doctorId = Auth::id();
        }

        $month = $request->month ?? date('Y-m');

        $query = DoctorAttendance::with('user') // <--- QUAN TRỌNG: Load thông tin bác sĩ
            ->whereMonth('date', Carbon::parse($month)->month)
            ->whereYear('date', Carbon::parse($month)->year)
            ->orderBy('date', 'desc'); // Sắp xếp ngày mới nhất lên đầu

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $attendances = $query->get();

        // Đếm số ngày đi làm
        $workingDays = $attendances->whereIn('status', ['present', 'late'])->count();

        // Lấy danh sách bác sĩ để Admin lọc (nếu cần)
        $doctors = User::where('type', 'doctor')->get(); // Hoặc logic lấy danh sách bác sĩ của bạn

        return view('doctor_attendances.index', compact(
            'attendances',
            'workingDays',
            'doctorId',
            'month',
            'doctors'
        ));
    }
 
    // Lưu chấm công
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required',
        ]);

        DoctorAttendance::create($request->all());

        return redirect()->route('doctor-attendances.index')
            ->with('success', 'Chấm công thành công');
    }

    // Form sửa
    public function edit(DoctorAttendance $doctorAttendance)
    {
        $doctors = User::role('doctor')->get();
        return view('doctor_attendances.edit', compact('doctorAttendance', 'doctors'));
    }

    // Cập nhật
    public function update(Request $request, DoctorAttendance $doctorAttendance)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required',
        ]);

        $doctorAttendance->update($request->all());

        return redirect()->route('doctor_attendances.index')
            ->with('success', 'Cập nhật thành công');
    }
   /**
     * Hành động 1: Check-in (Bắt đầu ca làm việc)
     */
    // public function checkIn(Request $request)
    // {
    //     $user = Auth::user();
    //     // Lấy giờ Việt Nam chuẩn xác
    //     $now = Carbon::now('Asia/Ho_Chi_Minh'); 
    //     $today = $now->format('Y-m-d');

    //     $exists = DoctorAttendance::where('doctor_id', $user->id)->where('date', $today)->exists();
    //     if ($exists) {
    //         return back()->with('error', 'Hôm nay bạn đã Check-in rồi!');
    //     }

    //     $status = $now->format('H:i:s') > self::START_TIME ? 'late' : 'present';
    //     $note = $status == 'late' ? 'Đi muộn lúc ' . $now->format('H:i') : ($request->note ?? null);

    //     DoctorAttendance::create([
    //         'doctor_id' => $user->id,
    //         'date'      => $today,
    //         'check_in'  => $now->format('H:i:s'),
    //         'status'    => $status,
    //         'note'      => $note
    //     ]);

    //     return back()->with('success', 'Đã Check-in thành công lúc ' . $now->format('H:i:s'));
    // }
public function checkIn(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now('Asia/Ho_Chi_Minh'); 
        $today = $now->format('Y-m-d');

        // 1. Kiểm tra xem đã check-in chưa
        $exists = DoctorAttendance::where('doctor_id', $user->id)->where('date', $today)->exists();
        if ($exists) {
            return back()->with('error', 'Hôm nay bạn đã Check-in rồi!');
        }

        // 2. Tính trạng thái
        // Nếu check-in sau 8h00 thì là 'late', ngược lại là 'present'
        $status = $now->format('H:i:s') > self::START_TIME ? 'late' : 'present';
        
        $shift = $request->input('shift', 'day'); // Lấy ca làm việc từ form (day/night)

        DoctorAttendance::create([
            'doctor_id' => $user->id,
            'date'      => $today,
            'check_in'  => $now->format('H:i:s'),
            'status'    => $status, // <--- Quan trọng: status này dùng để đếm công
            'shift'     => $shift,
            'note'      => $request->note
        ]);

        return back()->with('success', 'Đã Check-in thành công! Chúc bác sĩ làm việc hiệu quả.');
    }
    /**
     * Hành động 2: Check-out (Kết thúc ca làm việc)
     */
//    public function checkOut(Request $request)
//     {
//         $user = Auth::user();
//         // Lấy giờ Việt Nam chuẩn xác
//         $now = Carbon::now('Asia/Ho_Chi_Minh');
//         $today = $now->format('Y-m-d');

//         $attendance = DoctorAttendance::where('doctor_id', $user->id)
//                         ->where('date', $today)
//                         ->first();

//         if (!$attendance) {
//             return back()->with('error', 'Bạn chưa Check-in nên không thể Check-out!');
//         }
        
//         if ($attendance->check_out) {
//              return back()->with('error', 'Bạn đã Check-out rồi!');
//         }

//         $note = $attendance->note;
//         // Nếu có nhập ghi chú mới thì nối thêm vào
//         if($request->note) {
//             $note .= ' | ' . $request->note;
//         }
        
//         if ($now->format('H:i:s') < self::END_TIME) {
//             $note .= " | Về sớm lúc " . $now->format('H:i');
//         }

//         $attendance->update([
//             'check_out' => $now->format('H:i:s'),
//             'note'      => $note
//         ]);

//         return back()->with('success', 'Check-out thành công lúc ' . $now->format('H:i:s'));
//     }
public function checkOut(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $today = $now->format('Y-m-d');

        // 1. Tìm bản ghi hôm nay
        $attendance = DoctorAttendance::where('doctor_id', $user->id)
                        ->where('date', $today)
                        ->first();

        if (!$attendance) {
            return back()->with('error', 'Lỗi: Bạn chưa Check-in ngày hôm nay!');
        }
        
        if ($attendance->check_out) {
             return back()->with('error', 'Bạn đã Check-out trước đó rồi!');
        }

        // 2. TÍNH TOÁN TỔNG GIỜ LÀM (Total Hours)
        $checkInTime = Carbon::parse($attendance->check_in);
        $checkOutTime = $now;
        
        // Tính hiệu số phút rồi chia cho 60 để ra giờ (số thập phân)
        // Ví dụ: Làm 8 tiếng 30 phút = 8.5 giờ
        $totalHours = $checkOutTime->diffInMinutes($checkInTime) / 60;

        // 3. Cập nhật dữ liệu
        $note = $attendance->note;
        if ($now->format('H:i:s') < self::END_TIME && $attendance->shift == 'day') {
            $note .= " | Về sớm lúc " . $now->format('H:i');
        }

        $attendance->update([
            'check_out'   => $now->format('H:i:s'),
            'total_hours' => round($totalHours, 2), // <--- LƯU TỔNG GIỜ TẠI ĐÂY (Làm tròn 2 số lẻ)
            'note'        => $note
        ]);

        return back()->with('success', 'Đã kết thúc ca làm việc. Tổng thời gian: ' . round($totalHours, 2) . ' giờ.');
    }
    // Xóa
    public function destroy(DoctorAttendance $doctorAttendance)
    {
        $doctorAttendance->delete();

        return redirect()->route('doctor-attendances.index')
            ->with('success', 'Xóa thành công');
    }
    
}
