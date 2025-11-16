<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\User;

class DoctorPatientController extends Controller
{
    /**
     * 🧑‍⚕️ Danh sách bệnh nhân của bác sĩ đang đăng nhập
     */
    public function index()
    {
        // Lấy ID bác sĩ hiện tại
        $doctorId = Auth::id();

        // Lấy danh sách lịch hẹn có liên kết với bệnh nhân
        $appointments = Appointment::with(['user:id,name,gender,phone,patient_code'])
            ->where('doctor_id', $doctorId)
            ->whereIn('status', ['Đã xác nhận', 'Đang chờ khám', 'Đang khám', 'Hoàn thành'])
            ->orderByDesc('date')
            ->get();

        return view('doctor.patients.index', compact('appointments'));
    }

    /**
     * 📋 Xem chi tiết thông tin bệnh nhân
     */
    public function show($id)
    {
        $appointment = Appointment::with('user')
            ->where('doctor_id', Auth::id())
            ->findOrFail($id);

        return view('doctor.patients.show', compact('appointment'));
    }

    /**
     * ✏️ Sửa thông tin bệnh nhân
     */
    public function edit($id)
    {
        $patient = User::findOrFail($id);
        return view('doctor.patients.edit', compact('patient'));
    }

    /**
     * 💾 Cập nhật thông tin bệnh nhân
     */
    public function update(Request $request, $id)
    {
        $patient = User::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'nullable|string|max:20',
            'gender' => 'nullable|string|max:10',
        ]);

        $patient->update($validated);

        return redirect()->route('doctor.patients.index')
            ->with('success', 'Cập nhật thông tin bệnh nhân thành công.');
    }

    /**
     * ❌ Xóa bệnh nhân
     */
    public function destroy($id)
    {
        $patient = User::findOrFail($id);
        $patient->delete();

        return redirect()->route('doctor.patients.index')
            ->with('success', 'Đã xóa bệnh nhân khỏi danh sách.');
    }
}
