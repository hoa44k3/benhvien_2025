<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Support\Str;

class DoctorDiagnosisController extends Controller
{
    /**
     * 📋 Danh sách bệnh nhân có lịch hẹn đã xác nhận hoặc đang chờ
     */
   public function index()
{
    $appointments = Appointment::with('user')
        ->whereIn('status', ['Đã xác nhận', 'Đang chờ'])
        ->orderBy('created_at', 'asc')
        ->get();

    return view('doctor.diagnosis.index', compact('appointments'));
}


    /**
     * 🧑‍⚕️ Trang khám bệnh & kê đơn
     */
    public function show(Appointment $appointment)
    {
        // Khi bác sĩ bắt đầu khám thì chuyển trạng thái sang “Đã xác nhận”
        if ($appointment->status === 'Đang chờ') {
            $appointment->update(['status' => 'Đã xác nhận']);
        }

        return view('doctor.diagnosis.show', compact('appointment'));
    }

    /**
     * 💊 Lưu chẩn đoán & đơn thuốc
     */
    public function store(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'diagnosis' => 'required|string|max:1000',
            'note' => 'nullable|string|max:1000',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_name' => 'required|string|max:100',
            'medicines.*.dosage' => 'nullable|string|max:100',
            'medicines.*.frequency' => 'nullable|string|max:100',
            'medicines.*.duration' => 'nullable|string|max:100',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.price' => 'nullable|numeric|min:0',
        ]);

        $prescriptionCode = 'PRES-' . strtoupper(Str::random(8));

        $prescription = Prescription::create([
            'code' => $prescriptionCode,
            'appointment_id' => $appointment->id,
            // 'doctor_id' => auth()->guard('doctor')->id() ?? auth()->id(),
            'doctor_id' => auth('doctor')->id(), // ✅ Cách viết an toàn hơn
            'patient_id' => $appointment->user_id,
            'diagnosis' => $validated['diagnosis'],
            'note' => $validated['note'] ?? null,
            'status' => 'Đã duyệt',
        ]);

        foreach ($validated['medicines'] as $item) {
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medicine_name' => $item['medicine_name'],
                'dosage' => $item['dosage'] ?? '',
                'frequency' => $item['frequency'] ?? '',
                'duration' => $item['duration'] ?? '',
                'quantity' => $item['quantity'],
                'price' => $item['price'] ?? 0,
            ]);
        }

        $appointment->update(['status' => 'Hoàn thành']);

        return redirect()
            ->route('doctor.diagnosis.index')
            ->with('success', '✅ Chẩn đoán và đơn thuốc đã được lưu thành công!');
    }

    /**
     * 🧾 Xem chi tiết đơn thuốc
     */
    public function viewPrescription(Appointment $appointment)
    {
        $prescription = Prescription::with('items')
            ->where('appointment_id', $appointment->id)
            ->first();

        return view('doctor.diagnosis.prescription', compact('appointment', 'prescription'));
    }
}
