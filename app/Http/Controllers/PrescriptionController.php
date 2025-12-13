<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\User;
use App\Models\MedicalRecord;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
// app/Http/Controllers/PrescriptionController.php
use PDF; // Barryvdh\DomPDF\Facade\Pdf; hoặc use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PrescriptionController extends Controller
{
   public function index()
    {
        $prescriptions = Prescription::with(['doctor', 'patient', 'medicalRecord','items'])
            ->orderBy('id', 'desc')
            ->paginate(10);
// if ($prescriptions->isNotEmpty()) {
//     $first = $prescriptions->first();
    
//     // 👇 Sửa lại lệnh dd để xem chi tiết 1 viên thuốc
//     dd([
//         'ID Đơn thuốc' => $first->id,
//         'Accessor Total' => $first->total_amount, // Nếu cái này null -> Lỗi Model (Bước 1)
//         'CHI TIẾT 1 THUỐC' => $first->items->first()->toArray() // Soi kỹ dòng này
//     ]);
// }
        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $doctors = User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get();
        $patients = User::all();
        $records = MedicalRecord::all();

        return view('prescriptions.create', compact('doctors', 'patients', 'records'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:prescriptions',
            'doctor_id' => 'required',
            'patient_id' => 'required',
            'diagnosis' => 'nullable|string',
            'note' => 'nullable|string',
           'status' => 'nullable|in:Đang kê,Đã duyệt,Đã phát thuốc',
            'medical_record_id' => 'nullable|exists:medical_records,id'

        ]);

        // Nếu không gửi status -> lấy mặc định
        if (!$request->filled('status')) {
            $validated['status'] = 'Đang kê';
        }

        Prescription::create($validated);

        return redirect()->route('prescriptions.index')->with('success', 'Tạo đơn thuốc thành công!');
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
    $prescription->load('doctor','patient','items'); // load data
    $pdf = FacadePdf::loadView('prescriptions.pdf', compact('prescription'));

    $filename = $prescription->code . '.pdf';
    return $pdf->download($filename);
}

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();

        return response()->json(['success' => true]);
    }
}
