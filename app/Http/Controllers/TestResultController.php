<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TestResult;
use App\Models\User;
use App\Models\Department;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Storage;

class TestResultController extends Controller
{
   public function index()
    {
         $results = TestResult::with(['patient', 'doctor', 'department'])
        ->orderBy('id', 'desc')
        ->paginate(10);

    return view('test_results.index', compact('results'));
    }

    public function create()
    {
        $patients = User::role('user')->get();
        $doctors = User::role('doctor')->get();
        $departments = Department::all();
        $medicalRecords = MedicalRecord::all();

        return view('test_results.create', compact('patients','doctors','departments','medicalRecords'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'date' => 'required|date',
            'test_type' => 'required|string|max:255',
            'result' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $filePath = null;

        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('test_results', 'public');
        }

        TestResult::create([
            'user_id' => $request->user_id,
            'medical_record_id' => $request->medical_record_id,
            'date' => $request->date,
            'test_type' => $request->test_type,
            'result' => $request->result,
            'file_path' => $filePath,
            'doctor_id' => $request->doctor_id,
            'department_id' => $request->department_id,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('test_results.index')->with('success', 'Thêm kết quả xét nghiệm thành công!');
    }

    public function show(TestResult $testResult)
    {
        return view('test_results.show', compact('testResult'));
    }

    public function edit(TestResult $testResult)
    {
        $patients = User::role('user')->get();
        $doctors = User::role('doctor')->get();
        $departments = Department::all();
        $medicalRecords = MedicalRecord::all();

        return view('test_results.edit', compact('testResult','patients','doctors','departments','medicalRecords'));
    }

    public function update(Request $request, TestResult $testResult)
    {
        $request->validate([
            'user_id' => 'required',
            'date' => 'required|date',
            'test_type' => 'required|string|max:255',
            'result' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $filePath = $testResult->file_path;

        if ($request->hasFile('file_path')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_path')->store('test_results', 'public');
        }

        $testResult->update([
            'user_id' => $request->user_id,
            'medical_record_id' => $request->medical_record_id,
            'date' => $request->date,
            'test_type' => $request->test_type,
            'result' => $request->result,
            'file_path' => $filePath,
            'doctor_id' => $request->doctor_id,
            'department_id' => $request->department_id,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('test_results.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(TestResult $testResult)
    {
        if ($testResult->file_path) {
            Storage::disk('public')->delete($testResult->file_path);
        }

        $testResult->delete();

        return redirect()->route('test_results.index')->with('success', 'Xóa kết quả xét nghiệm thành công!');
    }
}
