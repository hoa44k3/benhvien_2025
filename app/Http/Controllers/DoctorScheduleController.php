<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Shift;
use Carbon\Carbon;

class DoctorScheduleController extends Controller
{
public function index()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    $today = \Carbon\Carbon::today()->toDateString();

    $appointments = \App\Models\Appointment::where('doctor_id', $user->id)
        ->whereDate('date', $today)
        ->orderBy('time', 'asc')
        ->get();

    $shifts = \App\Models\Shift::where('user_id', $user->id)
        ->whereDate('date', $today)
        ->get();

    return view('doctor.schedule.index', compact('appointments', 'shifts', 'today'));
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
    //   public function destroy(DoctorSchedule $doctorSchedule){
    //     $doctorSchedule->delete();
    //     return response()->json(['success'=>true]);
    // }
}
