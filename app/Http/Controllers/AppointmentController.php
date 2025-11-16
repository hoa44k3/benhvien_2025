<?php

namespace App\Http\Controllers;

use App\Helpers\AuditHelper;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Staff;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Hiển thị danh sách lịch hẹn
     */
    public function index()
    {
        $appointments = Appointment::with(['doctor', 'department', 'user'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Form thêm mới lịch hẹn
     */
    public function create()
    {
        $doctors = Staff::whereHas('role', function ($q) {
            $q->where('name', 'doctor');
        })->get();

        $departments = Department::all();
        $users = User::all();

        return view('appointments.create', compact('doctors', 'departments', 'users'));
    }

    /**
     * Lưu lịch hẹn mới
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'patient_phone' => 'nullable|string|max:20',
                'doctor_id' => 'required|exists:staff,id',
                'department_id' => 'nullable|exists:departments,id',
                'reason' => 'nullable|string',
                'diagnosis' => 'nullable|string',
                'notes' => 'nullable|string',
                'date' => 'required|date',
                'time' => 'required',
                'status' => 'required|in:Đang chờ,Đã xác nhận,Đang khám,Hoàn thành,Đã hẹn,Hủy',
            ]);

            // ✅ Lấy thông tin người dùng hiện tại
            $user = $request->user(); if (!$user) { return redirect()->back()->with('error', '⚠️ Bạn cần đăng nhập để đặt lịch khám.'); }

            // ✅ Tạo mã bệnh nhân
            $patientCode = 'BN' . str_pad($user->id, 5, '0', STR_PAD_LEFT);

            // ✅ Sinh mã lịch hẹn tự động (nếu chưa có)
            $code = 'LH' . now()->format('YmdHis');

            // ✅ Lưu dữ liệu
            $appointment = Appointment::create([
                'code' => $code,
                'user_id' => $user->id,
                'doctor_id' => $validated['doctor_id'],
                'department_id' => $validated['department_id'] ?? null,
                'patient_code' => $patientCode,
                'patient_name' => $validated['patient_name'],
                'patient_phone' => $validated['patient_phone'] ?? null,
                'reason' => $validated['reason'] ?? null,
                'diagnosis' => $validated['diagnosis'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'date' => $validated['date'],
                'time' => Carbon::parse($validated['time'])->format('Y-m-d H:i:s'),
                'status' => $validated['status'],
            ]);

            AuditHelper::log('Thêm lịch hẹn', $appointment->patient_name, 'Thành công');

            return redirect()->route('appointments.index')->with('success', '✅ Thêm lịch hẹn thành công!');
        } catch (\Throwable $e) {
            AuditHelper::log('Thêm lịch hẹn', $request->patient_name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', '❌ Lỗi khi thêm lịch hẹn: ' . $e->getMessage());
        }
    }

    /**
     * Form chỉnh sửa lịch hẹn
     */
    public function edit(Appointment $appointment)
    {
        $doctors = Staff::whereHas('role', function ($q) {
            $q->where('name', 'doctor');
        })->get();

        $departments = Department::all();
        $users = User::all();

        return view('appointments.edit', compact('appointment', 'doctors', 'departments', 'users'));
    }

    /**
     * Cập nhật lịch hẹn
     */
    public function update(Request $request, Appointment $appointment)
    {
        try {
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'patient_phone' => 'nullable|string|max:20',
                'doctor_id' => 'required|exists:staff,id',
                'department_id' => 'nullable|exists:departments,id',
                'reason' => 'nullable|string',
                'diagnosis' => 'nullable|string',
                'notes' => 'nullable|string',
                'date' => 'required|date',
                'time' => 'required',
                'status' => 'required|in:Đang chờ,Đã xác nhận,Đang khám,Hoàn thành,Đã hẹn,Hủy',
            ]);

            $appointment->update([
                'patient_name' => $validated['patient_name'],
                'patient_phone' => $validated['patient_phone'] ?? null,
                'doctor_id' => $validated['doctor_id'],
                'department_id' => $validated['department_id'] ?? null,
                'reason' => $validated['reason'] ?? null,
                'diagnosis' => $validated['diagnosis'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'date' => $validated['date'],
                'time' => Carbon::parse($validated['time'])->format('Y-m-d H:i:s'),
                'status' => $validated['status'],
            ]);

            AuditHelper::log('Cập nhật lịch hẹn', $appointment->patient_name, 'Thành công');

            return redirect()->route('appointments.index')->with('success', '✅ Cập nhật lịch hẹn thành công!');
        } catch (\Throwable $e) {
            AuditHelper::log('Cập nhật lịch hẹn', $appointment->patient_name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', '❌ Lỗi khi cập nhật lịch hẹn: ' . $e->getMessage());
        }
    }

    /**
     * Xóa lịch hẹn
     */
    public function destroy(Appointment $appointment)
    {
        try {
            $appointment->delete();
            AuditHelper::log('Xóa lịch hẹn', $appointment->patient_name, 'Thành công');
            return redirect()->route('appointments.index')->with('success', '🗑️ Đã xóa lịch hẹn thành công!');
        } catch (\Throwable $e) {
            AuditHelper::log('Xóa lịch hẹn', $appointment->patient_name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', '❌ Không thể xóa: ' . $e->getMessage());
        }
    }
}
