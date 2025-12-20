<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorLeave;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveStatusMail;
use App\Mail\PatientRescheduleMail;

class LeaveController extends Controller
{
    // 1. Danh sách đơn nghỉ (index)
    public function index()
    {
        $leaves = DoctorLeave::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('leaves.index', compact('leaves'));
    }

    // 2. Form tạo đơn (create) - Dành cho Admin tạo hộ (nếu cần)
    public function create()
    {
        $doctors = User::where('role', 'doctor')->get();
        return view('leaves.create', compact('doctors'));
    }

    // 3. Lưu đơn mới (store)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        DoctorLeave::create($request->all() + ['status' => 'approved']); // Admin tạo thì duyệt luôn
        return redirect()->route('leaves.index')->with('success', 'Đã tạo lịch nghỉ cho bác sĩ.');
    }

    // 4. Xem chi tiết (show)
    public function show(DoctorLeave $leave)
    {
        return view('leaves.show', compact('leave'));
    }

    // 5. Form sửa (edit)
    public function edit(DoctorLeave $leave)
    {
        return view('leaves.edit', compact('leave'));
    }

    // 6. Cập nhật trạng thái (update) - Xử lý Duyệt/Từ chối
    public function update(Request $request, DoctorLeave $leave)
    {
        // Nếu là update trạng thái (Duyệt/Từ chối)
        if ($request->has('status')) {
            $status = $request->status;
            $leave->update([
                'status' => $status,
                'admin_note' => $request->input('admin_note')
            ]);

            // --- LOGIC XỬ LÝ KHI DUYỆT ---
            if ($status == 'approved') {
                // Hủy lịch hẹn & Gửi mail bệnh nhân
                $this->cancelAppointmentsAndNotify($leave);
            }

            // Gửi mail thông báo cho bác sĩ
            if ($leave->user && $leave->user->email) {
                try {
                    Mail::to($leave->user->email)->send(new LeaveStatusMail($leave));
                } catch (\Exception $e) {}
            }

            return back()->with('success', 'Đã cập nhật trạng thái đơn nghỉ phép.');
        }

        // Nếu là update thông tin thường (sửa ngày, lý do...)
        $leave->update($request->all());
        return redirect()->route('leaves.index')->with('success', 'Cập nhật thông tin thành công.');
    }

    // 7. Xóa đơn (destroy)
    public function destroy(DoctorLeave $leave)
    {
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Đã xóa đơn nghỉ phép.');
    }

    // --- Hàm phụ trợ tách riêng logic hủy lịch ---
   // app/Http/Controllers/LeaveController.php

private function cancelAppointmentsAndNotify($leave)
{
    $affectedAppointments = Appointment::where('doctor_id', $leave->user_id)
        ->whereBetween('date', [$leave->start_date, $leave->end_date])
        ->whereIn('status', ['Đang chờ', 'Đã xác nhận']) // Lấy các lịch chưa hoàn thành
        ->with('user')
        ->get();

    foreach ($affectedAppointments as $app) {
        // --- SỬA LẠI DÒNG NÀY ---
        // Phải dùng đúng từ khóa 'Hủy' như trong Migration khai báo
        $app->update(['status' => 'Hủy']); 

        if ($app->user && $app->user->email) {
            try {
                // Nội dung mail vẫn giữ nguyên để báo lý do cho khách
                Mail::to($app->user->email)->send(new PatientRescheduleMail($app, $leave->user->name));
            } catch (\Exception $e) {}
        }
    }
}
}