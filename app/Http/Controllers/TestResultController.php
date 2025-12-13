<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TestResult;
use App\Models\User;
use App\Models\Department;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TestResultController extends Controller
{
    public function index()
    {
        $results = TestResult::with(['patient', 'doctor', 'department', 'medicalRecord']) // Sử dụng relationship đúng tên trong Model
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('test_results.index', compact('results'));
    }

    public function create()
    {
        $patients = User::whereHas('roles', fn($q) => $q->where('name', 'patient'))->get();
        $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
        $departments = Department::all();
        $medicalRecords = MedicalRecord::latest()->take(50)->get();

        return view('test_results.create', compact('patients', 'doctors', 'departments', 'medicalRecords'));
    }

    /**
     * BƯỚC 1: CHỈ ĐỊNH (Status -> Pending)
     */
    public function store(Request $request)
    {
        // Validate cơ bản
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'test_name' => 'required|string|max:255',
            'medical_record_id' => 'nullable|exists:medical_records,id',
        ]);

        $data = $request->all();

        // Tự động set mặc định
        $data['date'] = $request->date ?? now();
        $data['status'] = 'pending'; // Mặc định là Chờ kết quả
        $data['result'] = null;      // Chưa có kết quả
        $data['created_by'] = Auth::id();

        // Upload file (nếu có ngay lúc tạo)
        if ($request->hasFile('file_path')) {
            $data['file_main'] = $request->file('file_path')->store('test_results', 'public'); // Lưu vào cột file_main theo migration
            $data['status'] = 'completed'; // Nếu có file luôn thì coi như xong
        }

        TestResult::create($data);

        // Quay lại trang hồ sơ bệnh án để bác sĩ làm việc tiếp
        if ($request->medical_record_id) {
            return redirect()->route('medical_records.show', $request->medical_record_id)
                             ->with('success', 'Đã chỉ định xét nghiệm thành công.');
        }

        return redirect()->route('test_results.index')->with('success', 'Tạo xét nghiệm thành công');
    }

    public function show(TestResult $testResult)
    {
        return view('test_results.show', compact('testResult'));
    }

    // public function edit(TestResult $testResult)
    // {
    //     // Code load view edit...
    //     return view('test_results.edit', compact('testResult'));
    // }
    public function edit(TestResult $testResult)
{
    // Lấy danh sách để đổ vào dropdown
    $patients = User::whereHas('roles', fn($q) => $q->where('name', 'patient'))->get(); 
    // Nếu chưa có role 'patient', bạn có thể dùng User::all() tạm thời:
    // $patients = User::all();

    $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
    $departments = Department::all();
    
    // Lấy 50 hồ sơ gần nhất để chọn (nếu cần đổi hồ sơ)
    $medicalRecords = MedicalRecord::orderBy('created_at', 'desc')->take(50)->get();

    return view('test_results.edit', compact('testResult', 'patients', 'doctors', 'departments', 'medicalRecords'));
}

    /**
     * BƯỚC 2 & 3: NHẬP KẾT QUẢ & DUYỆT (Status -> Completed/Reviewed)
     */
    public function update(Request $request, TestResult $testResult)
    {
        // Cho phép cập nhật từng phần (không bắt buộc nhập lại tất cả)
        $data = $request->validate([
            'result' => 'nullable|string',
            'evaluation' => 'nullable|string', // Bác sĩ đánh giá
            'file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10000',
        ]);

        // 1. Xử lý File
        if ($request->hasFile('file')) {
            // Xóa file cũ
            if ($testResult->file_main && Storage::disk('public')->exists($testResult->file_main)) {
                Storage::disk('public')->delete($testResult->file_main);
            }
            $testResult->file_main = $request->file('file')->store('test_results', 'public');
            $testResult->status = 'completed'; // Có file -> Đã có KQ
        }

        // 2. Cập nhật dữ liệu text
        if ($request->filled('result')) {
            $testResult->result = $request->result;
            // Nếu chưa duyệt thì chuyển thành đã có KQ
            if ($testResult->status == 'pending') {
                $testResult->status = 'completed'; 
            }
        }

        if ($request->filled('evaluation') || $request->filled('diagnosis')) {
            $testResult->evaluation = $request->evaluation ?? $request->diagnosis;
            // Có đánh giá của bác sĩ -> Đã duyệt
            $testResult->status = 'reviewed'; 
        }

        // Lưu các trường khác nếu có gửi lên
        $testResult->fill($request->except(['file', 'result', 'evaluation', 'status'])); // Status tự quản lý
        $testResult->save();

        // Quay lại trang hồ sơ
        if ($testResult->medical_record_id) {
            return redirect()->route('medical_records.show', $testResult->medical_record_id)
                             ->with('success', 'Cập nhật kết quả thành công.');
        }

        return redirect()->route('test_results.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(TestResult $testResult)
    {
        if ($testResult->file_main && Storage::disk('public')->exists($testResult->file_main)) {
            Storage::disk('public')->delete($testResult->file_main);
        }
        $testResult->delete();
        return back()->with('success', 'Đã xóa xét nghiệm.');
    }
}