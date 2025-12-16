<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorLeave;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveStatusMail;
use App\Mail\PatientRescheduleMail;

class AdminLeaveController extends Controller
{
    public function index() {
        $leaves = DoctorLeave::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.leaves.index', compact('leaves'));
    }

    // Xử lý Duyệt/Từ chối
    public function updateStatus(Request $request, $id) {
        $leave = DoctorLeave::with('user')->findOrFail($id);
        $status = $request->input('status'); // 'approved' hoặc 'rejected'
        
        $leave->update([
            'status' => $status,
            'admin_note' => $request->input('admin_note')
        ]);

        // LOGIC KHI DUYỆT ĐƠN (APPROVED)
        if ($status == 'approved') {
            // 1. Tìm các lịch hẹn bị ảnh hưởng (Trạng thái đang chờ)
            $affectedAppointments = Appointment::where('doctor_id', $leave->user_id)
                ->whereBetween('date', [$leave->start_date, $leave->end_date])
                ->whereIn('status', ['Đang chờ', 'Đã xác nhận']) // Chỉ hủy các lịch chưa khám
                ->with('user') // Lấy thông tin bệnh nhân để gửi mail
                ->get();

            // 2. Xử lý từng lịch hẹn
            foreach ($affectedAppointments as $app) {
                // Cập nhật trạng thái lịch hẹn
                $app->update(['status' => 'Hủy lịch (Bác sĩ nghỉ)']);

                // Gửi mail cho bệnh nhân (Nếu có email)
                if ($app->user && $app->user->email) {
                    try {
                        Mail::to($app->user->email)->send(new PatientRescheduleMail($app, $leave->user->name));
                    } catch (\Exception $e) {
                        // Log lỗi mail nhưng không chặn quy trình
                    }
                }
            }
        }

        // 3. Gửi mail thông báo cho Bác sĩ (Dù duyệt hay từ chối)
        if ($leave->user->email) {
            Mail::to($leave->user->email)->send(new LeaveStatusMail($leave));
        }

        return back()->with('success', 'Đã xử lý đơn nghỉ phép và thông báo cho các bên liên quan.');
    }
}
