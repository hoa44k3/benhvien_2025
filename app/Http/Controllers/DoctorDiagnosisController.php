<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DoctorDiagnosisController extends Controller
{
    /**
     * 📋 Danh sách bệnh nhân có lịch hẹn đã xác nhận hoặc đang chờ
     */
    public function index()
    {
        // Lấy các lịch hẹn chưa hoàn thành
        $appointments = Appointment::whereIn('status', ['Đã xác nhận', 'Đang chờ'])
            // ->where('doctor_id', auth()->id()) // Nếu muốn chỉ hiện bệnh nhân của bác sĩ này
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        return view('doctor.diagnosis.index', compact('appointments'));
    }


    /**
     * 🧑‍⚕️ Trang khám bệnh & kê đơn
     */
    // 2. Giao diện khám bệnh
    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Chuyển trạng thái sang "Đang khám" nếu cần
        if ($appointment->status === 'Đang chờ') {
            $appointment->update(['status' => 'Đã xác nhận']);
        }

        return view('doctor.diagnosis.show', compact('appointment'));
    }

    /**
     * 💊 Lưu chẩn đoán & đơn thuốc
     */
   // 3. Lưu kết quả khám
   public function store(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Validate dữ liệu
        $request->validate([
            'diagnosis' => 'required|string|max:1000',
            'note' => 'nullable|string|max:1000',
            'medicine_name' => 'required|array',
            'medicine_name.*' => 'required|string',
            'medicine_quantity' => 'required|array',
            'medicine_quantity.*' => 'required|integer|min:1',
            'medicine_usage' => 'required|array',
        ]);

        // 1. Tạo Đơn thuốc (Prescription)
        // SỬA: 'status' phải là 'Đang kê' (để khớp với enum của bảng prescriptions)
        $prescription = Prescription::create([
            'code' => 'DT-' . strtoupper(Str::random(8)),
            'appointment_id' => $appointment->id,
            'doctor_id' => $appointment->doctor_id,
            'patient_id' => $appointment->user_id,
            'diagnosis' => $request->diagnosis,
            'note' => $request->note,
            'status' => 'Đang kê', // <--- QUAN TRỌNG: Giá trị này có trong list enum
        ]);

        // 2. Lưu chi tiết thuốc
        $names = $request->medicine_name;
        $quantities = $request->medicine_quantity;
        $usages = $request->medicine_usage;

        for ($i = 0; $i < count($names); $i++) {
            if (!empty($names[$i])) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_name' => $names[$i],
                    'quantity' => $quantities[$i],
                    'instruction' => $usages[$i] ?? '',
                    'price' => 0,
                ]);
            }
        }

        // 3. Cập nhật trạng thái Lịch hẹn (Appointments)
        // SỬA: 'status' phải là 'Hoàn thành' (để khớp với logic của bảng appointments)
        // Đừng dùng 'Đang kê' ở đây vì bảng appointments không hiểu giá trị đó
        $appointment->update(['status' => 'Hoàn thành']); 

        return redirect()->route('doctor.diagnosis.index')
            ->with('success', '✅ Đã hoàn thành ca khám và kê đơn!');
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
    /**
     * 📹 Chức năng Gọi Video (Tích hợp Jitsi Meet)
     */
    public function videoCall($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Tạo tên phòng unique dựa trên Mã lịch hẹn để tránh người khác vào nhầm
        // Ví dụ: SmartHospital_LH123456
        $roomName = 'SmartHospital_' . $appointment->code;

        // Lấy thông tin người dùng hiện tại (Bác sĩ) để hiển thị tên trong cuộc gọi
        $userName = Auth::user()->name;
        $userEmail = Auth::user()->email;

        return view('doctor.diagnosis.video_call', compact('appointment', 'roomName', 'userName', 'userEmail'));
    }
}
