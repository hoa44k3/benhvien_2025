<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\User;
use App\Models\MedicalRecord;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class PrescriptionController extends Controller
{
    public function index()
    {
        // Load danh sách đơn thuốc
        $prescriptions = Prescription::with(['doctor', 'patient', 'medicalRecord', 'items'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('prescriptions.index', compact('prescriptions'));
    }

    // public function create()
    // {
    //     $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
    //     $patients = User::all();
    //     $records = MedicalRecord::all();

    //     return view('prescriptions.create', compact('doctors', 'patients', 'records'));
    // }
    public function create(Request $request) // 1. Thêm Request $request vào tham số
    {
        $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
        $patients = User::all();
        
        // --- LOGIC MỚI: TỰ ĐỘNG ĐIỀN DỮ LIỆU ---
        $prefilled = null;
        $newCode = 'DT-' . strtoupper(Str::random(8)); // Tự sinh mã đơn thuốc luôn cho tiện

        if ($request->has('medical_record_id')) {
            $record = MedicalRecord::with(['user', 'doctor'])->find($request->get('medical_record_id'));
            if ($record) {
                $prefilled = [
                    'medical_record_id' => $record->id,
                    'patient_id' => $record->user_id,
                    'patient_name' => $record->user->name,
                    'doctor_id' => $record->doctor_id,
                    'doctor_name' => $record->doctor->name ?? 'Chưa chỉ định',
                    'diagnosis' => $record->diagnosis ?? $record->diagnosis_primary, // Lấy luôn chẩn đoán
                ];
            }
        }
        
        // Truyền thêm biến $prefilled và $newCode sang view
        return view('prescriptions.create', compact('doctors', 'patients', 'prefilled', 'newCode'));
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'code' => 'required|unique:prescriptions',
    //         'doctor_id' => 'required',
    //         'patient_id' => 'required',
    //         'diagnosis' => 'nullable|string',
    //         'note' => 'nullable|string',
    //         'status' => 'nullable|in:Đang kê,Đã duyệt,Đã phát thuốc',
    //         'medical_record_id' => 'nullable|exists:medical_records,id'
    //     ]);

    //     // Nếu không gửi status -> lấy mặc định
    //     if (!$request->filled('status')) {
    //         $validated['status'] = 'Đang kê';
    //     }

    //     // --- CẬP NHẬT MỚI: Luôn set tổng tiền bằng 0 ---
    //     $validated['total_amount'] = 0; 

    //     Prescription::create($validated);

    //     return redirect()->route('prescriptions.index')->with('success', 'Tạo đơn thuốc thành công!');
    // }
public function store(Request $request)
{
    $validated = $request->validate([
        'code' => 'required|unique:prescriptions',
        'doctor_id' => 'required|exists:users,id', // Đảm bảo ID tồn tại
        'patient_id' => 'required|exists:users,id',
        'diagnosis' => 'nullable|string',
        'note' => 'nullable|string',
        'status' => 'nullable|in:Đang kê,Đã duyệt,Đã phát thuốc',
        'medical_record_id' => 'nullable|exists:medical_records,id'
    ]);

    // Nếu không gửi status -> lấy mặc định
    if (!$request->filled('status')) {
        $validated['status'] = 'Đang kê';
    }

    $validated['total_amount'] = 0; 

    // Tạo đơn thuốc
    $prescription = Prescription::create($validated);

    // CHUYỂN HƯỚNG: Sau khi tạo xong Header đơn thuốc -> Chuyển sang trang thêm chi tiết thuốc (Edit)
    // Thay vì về index, ta về edit để bác sĩ thêm thuốc luôn
    return redirect()->route('prescriptions.edit', $prescription->id)
                     ->with('success', 'Đã tạo đơn thuốc. Vui lòng thêm thuốc vào đơn.');
}
    public function edit(Prescription $prescription)
    {
        $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
        $patients = User::all();
        $records = MedicalRecord::all();

        return view('prescriptions.edit', compact('prescription', 'doctors', 'patients', 'records'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $validated = $request->validate([
            'doctor_id' => 'required',
            'patient_id' => 'required',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'diagnosis' => 'nullable|string',
            'note' => 'nullable|string',
            'status' => 'required|in:Đang kê,Đã duyệt,Đã phát thuốc'
        ]);

        // Đảm bảo không update giá tiền
        $validated['total_amount'] = 0;

        $prescription->update($validated);

        return redirect()->route('prescriptions.index')
            ->with('success', 'Cập nhật đơn thuốc thành công!');
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['doctor', 'patient', 'items']);
        return view('prescriptions.show', compact('prescription'));
    }

    public function downloadPdf(Prescription $prescription)
    {
        $prescription->load('doctor','patient','items'); 
        $pdf = FacadePdf::loadView('prescriptions.pdf', compact('prescription'));
        return $pdf->download($prescription->code . '.pdf');
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();
        return redirect()->back()->with('success', 'Xóa đơn thuốc thành công.');
    }
}