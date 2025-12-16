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
  public function index(Request $request)
    {
        $doctorId = Auth::id();
        $query = Appointment::with(['user']) // Load quan hệ user để lấy tên, sđt
            ->where('doctor_id', $doctorId);

        // Tìm kiếm (nếu có)
        if ($request->has('search') && $request->search != '') {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('patient_name', 'like', "%{$keyword}%")
                  ->orWhere('patient_phone', 'like', "%{$keyword}%");
            });
        }

        // Lấy tất cả trạng thái để bác sĩ theo dõi lịch sử
        $appointments = $query->orderByDesc('date')
                              ->orderBy('time', 'asc')
                              ->paginate(10); // Phân trang cho đẹp

        return view('doctor.patients.index', compact('appointments'));
    }

  /**
     * 📋 Xem chi tiết hồ sơ bệnh nhân (Lịch sử khám cũ)
     */
    public function show($id)
    {
        // Lấy thông tin lịch hẹn
        $appointment = Appointment::with(['user', 'medicalRecord', 'prescription'])
            ->where('doctor_id', Auth::id())
            ->findOrFail($id);

        // Lấy lịch sử các lần khám trước của bệnh nhân này (nếu có)
        $history = Appointment::where('user_id', $appointment->user_id)
            ->where('id', '!=', $id)
            ->where('status', 'Hoàn thành')
            ->orderByDesc('date')
            ->get();

        return view('doctor.patients.show', compact('appointment', 'history'));
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
