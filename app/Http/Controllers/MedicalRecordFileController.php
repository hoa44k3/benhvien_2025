<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\MedicalRecordFile;
class MedicalRecordFileController extends Controller
{
   public function index()
    {
        $files = MedicalRecordFile::with(['medicalRecord', 'uploader'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('medical_record_files.index', compact('files'));
    }

    public function create()
    {
        $records = MedicalRecord::all();
        return view('medical_record_files.create', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'file' => 'required|file|max:20480',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('file');

        $path = $file->store('medical_files', 'public');

        MedicalRecordFile::create([
            'medical_record_id' => $request->medical_record_id,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'title' => $request->title,
            'description' => $request->description,
            'uploaded_by' => $request->user()->id,

            'status' => 'active',
        ]);

        return redirect()->route('medical_record_files.index')
            ->with('success', 'Tải file thành công');
    }

    public function show($id)
    {
        $file = MedicalRecordFile::with(['medicalRecord', 'uploader'])->findOrFail($id);
        return view('medical_record_files.show', compact('file'));
    }

    public function edit($id)
    {
        $file = MedicalRecordFile::findOrFail($id);
        $records = MedicalRecord::all();
        return view('medical_record_files.edit', compact('file', 'records'));
    }

    public function update(Request $request, $id)
    {
        $file = MedicalRecordFile::findOrFail($id);

        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'file' => 'nullable|file|max:20480',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,archived',
        ]);

        $data = [
            'medical_record_id' => $request->medical_record_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ];

        if ($request->hasFile('file')) {
            $f = $request->file('file');

            $path = $f->store('medical_files', 'public');

            $data['file_path'] = $path;
            $data['original_name'] = $f->getClientOriginalName();
            $data['file_type'] = $f->getClientOriginalExtension();
            $data['mime_type'] = $f->getMimeType();
            $data['file_size'] = $f->getSize();
        }

        $file->update($data);

        return redirect()->route('medical_record_files.index')
            ->with('success', 'Cập nhật file thành công');
    }

    public function destroy($id)
    {
        $file = MedicalRecordFile::findOrFail($id);
        $file->delete();

        return response()->json(['success' => true]);
    }
}
