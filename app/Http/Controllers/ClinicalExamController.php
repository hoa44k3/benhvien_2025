<?php

namespace App\Http\Controllers;
use App\Models\ClinicalExam;
use App\Models\MedicalRecord;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ClinicalExamController extends Controller
{
    public function index()
    {
        $exams = ClinicalExam::with('medicalRecord.user', 'enteredBy')->latest()->paginate(10);
        return view('clinical_exams.index', compact('exams'));
    }

    public function create()
    {
        $records = MedicalRecord::all();
        return view('clinical_exams.create', compact('records'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'entered_by' => 'nullable|exists:users,id',
            'temperature' => 'nullable|numeric',
            'blood_pressure' => 'nullable|string|max:50',
            'pulse' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'spo2' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'bmi' => 'nullable|numeric',
            'exam_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'measurements' => 'nullable|array',
        ]);
// Map dữ liệu từ form (heart_rate) sang DB (pulse) nếu tên khác nhau
        if($request->has('heart_rate')) {
            $data['pulse'] = $request->heart_rate;
        }

        $data['entered_by'] = Auth::id();
        ClinicalExam::create($data);
return redirect()->route('medical_records.show', $request->medical_record_id)
                         ->with('success', 'Đã lưu chỉ số sinh tồn thành công!');
        // return redirect()->route('clinical_exams.index')->with('success', 'Clinical Exam created successfully.');
    }

    // public function show(ClinicalExam $clinicalExam)
    // {
    //     return view('clinical_exams.show', compact('clinicalExam'));
    // }

    public function edit(ClinicalExam $clinicalExam)
    {
        $records = MedicalRecord::all();
        return view('clinical_exams.edit', compact('clinicalExam', 'records'));
    }

    public function update(Request $request, ClinicalExam $clinicalExam)
    {
        $data = $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'entered_by' => 'nullable|exists:users,id',
            'temperature' => 'nullable|numeric',
            'blood_pressure' => 'nullable|string|max:50',
            'pulse' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'spo2' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'bmi' => 'nullable|numeric',
            'exam_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'measurements' => 'nullable|array',
        ]);

        $clinicalExam->update($data);
// Quay lại trang chi tiết hồ sơ gốc
        return redirect()->route('medical_records.show', $clinicalExam->medical_record_id)
                         ->with('success', 'Cập nhật thành công.');
        // return redirect()->route('clinical_exams.index')->with('success', 'Clinical Exam updated successfully.');
    }

   public function destroy(ClinicalExam $clinicalExam)
    {
        $medicalRecordId = $clinicalExam->medical_record_id;
        $clinicalExam->delete();
        return redirect()->route('medical_records.show', $medicalRecordId)
                         ->with('success', 'Đã xóa kết quả khám.');
    }
}
