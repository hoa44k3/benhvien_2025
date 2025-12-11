<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\TestResult;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Department;
class MedicalRecordController extends Controller
{
public function index()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem hồ sơ bệnh án.');
    }

    $medicalRecords = MedicalRecord::where('user_id', $user->id)
        ->orderBy('date', 'desc')
        ->get();

    return view('medical_records.index', compact('medicalRecords', 'user'));
}

    // Form thêm hồ sơ
  public function create()
{
    $users = User::all(); // Lấy tất cả user
    $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
    $departments = Department::all();

    return view('medical_records.create', compact('users', 'doctors', 'departments'));
}

    public function store(Request $request)
    {
          $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string|max:255',
        'date' => 'required|date',
        'doctor_id' => 'nullable|exists:users,id',
        'department_id' => 'nullable|exists:departments,id',
        'appointment_id' => 'nullable|exists:appointments,id',
        'diagnosis' => 'nullable|string',
        'diagnosis_primary' => 'nullable|string|max:255',
        'diagnosis_secondary' => 'nullable|string|max:255',
        'treatment' => 'nullable|string',
        'symptoms' => 'nullable|string',
        'vital_signs' => 'nullable|array', // nhận dạng json
        'status' => 'required|in:chờ_khám,đang_khám,đã_khám,hủy',
        'next_checkup' => 'nullable|date',
    ]);
  // Chuyển vital_signs thành JSON nếu có
    if ($request->has('vital_signs')) {
        $data['vital_signs'] = json_encode($request->vital_signs);
    }
        MedicalRecord::create($request->all());

        return redirect()->route('medical_records.index')->with('success', 'Thêm hồ sơ bệnh án thành công.');
    }

    // Form chỉnh sửa
  public function edit(MedicalRecord $medical_record)
{
    $users = User::all(); 
    $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
    $departments = Department::all();

    return view('medical_records.edit', [
        'medical_record' => $medical_record,
        'users' => $users,
        'doctors' => $doctors,
        'departments' => $departments
    ]);
}


    public function update(Request $request, MedicalRecord $medical_record)
    {
        $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string|max:255',
        'date' => 'required|date',
        'doctor_id' => 'nullable|exists:users,id',
        'department_id' => 'nullable|exists:departments,id',
        'appointment_id' => 'nullable|exists:appointments,id',
        'diagnosis' => 'nullable|string',
        'diagnosis_primary' => 'nullable|string|max:255',
        'diagnosis_secondary' => 'nullable|string|max:255',
        'treatment' => 'nullable|string',
        'symptoms' => 'nullable|string',
        'vital_signs' => 'nullable|array',
        'status' => 'required|in:chờ_khám,đang_khám,đã_khám,hủy',
        'next_checkup' => 'nullable|date',
    ]);
 if ($request->has('vital_signs')) {
        $data['vital_signs'] = json_encode($request->vital_signs);
    }

        $medical_record->update($request->all());

        return redirect()->route('medical_records.index')->with('success', 'Cập nhật hồ sơ bệnh án thành công.');
    }

    public function show(MedicalRecord $medical_record)
{
    $medical_record->load('user', 'doctor', 'department', 'prescriptions', 'testResults', 'files'); 

    return view('medical_records.show', compact('medical_record'));
}

    public function download($id)
    {
        $record = MedicalRecord::findOrFail($id);

        // Tạo PDF hoặc file text — tạm thời xuất JSON cho dễ kiểm tra
        return response()->json([
            'message' => 'Download file thành công!',
            'record' => $record,
        ]);
    }
public function complete(MedicalRecord $medical_record)
{
    // Ví dụ: đổi trạng thái hồ sơ thành 'Hoàn thành'
    $medical_record->status = 'Hoàn thành';
    $medical_record->save();

    return redirect()->route('medical_records.show', $medical_record)
                     ->with('success', 'Hồ sơ đã được hoàn tất.');
}

    // Xóa hồ sơ
    public function destroy(MedicalRecord $medical_record)
    {
        $medical_record->delete();
        return back()->with('success', 'Xóa hồ sơ thành công!');
    }
}
