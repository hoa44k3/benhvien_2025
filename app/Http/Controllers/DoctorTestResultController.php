<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TestResult;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DoctorTestResultController extends Controller
{
    /**
     * 📋 Danh sách kết quả xét nghiệm
     */
    public function index(Request $request)
    {
        $doctorId = Auth::id();
        
        $query = TestResult::with(['user', 'appointment'])
            ->where('doctor_id', $doctorId)
            ->orderBy('created_at', 'desc');

        // Tìm kiếm theo tên bệnh nhân
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $results = $query->paginate(10);

        return view('doctor.test_results.index', compact('results'));
    }

    /**
     * 📤 Upload kết quả xét nghiệm (Nếu bác sĩ tự làm, ví dụ Siêu âm)
     */
    public function create()
    {
        // Lấy danh sách bệnh nhân đang khám để chọn
        $patients = Appointment::where('doctor_id', Auth::id())
            ->whereIn('status', ['Đang khám', 'Đã xác nhận'])
            ->with('user')
            ->get();
            
        return view('doctor.test_results.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required',
            'test_name' => 'required|string',
            'diagnosis' => 'nullable|string',
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // Ảnh hoặc PDF
        ]);

        // Xử lý upload file
        $fileName = time() . '_' . $request->file->getClientOriginalName();
        $filePath = $request->file('file')->storeAs('uploads/test_results', $fileName, 'public');

        // Lấy thông tin bệnh nhân từ lịch hẹn
        $appointment = Appointment::findOrFail($request->appointment_id);

        TestResult::create([
            'user_id' => $appointment->user_id,
            'doctor_id' => Auth::id(),
            'appointment_id' => $appointment->id, // Nếu bảng test_results có cột này
            'test_name' => $request->test_name,
            'diagnosis' => $request->diagnosis,
            'file_path' => $filePath, // Lưu đường dẫn
            'result' => 'Đã có kết quả',
        ]);

        return redirect()->route('doctor.test_results.index')->with('success', 'Đã tải lên kết quả xét nghiệm!');
    }
}
