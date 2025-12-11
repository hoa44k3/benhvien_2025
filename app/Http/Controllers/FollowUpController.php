<?php

namespace App\Http\Controllers;
use App\Models\FollowUp;
use App\Models\User;
use App\Models\MedicalRecord;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function index()
    {
        $followUps = FollowUp::with('patient','doctor','medicalRecord')->get();
        return view('follow_ups.index', compact('followUps'));
    }

    public function create()
    {
        $patients = User::whereHas('roles', fn($q)=>$q->where('name','patient'))->get();
        $doctors = User::whereHas('roles', fn($q)=>$q->where('name','doctor'))->get();
        $records = MedicalRecord::all();
        return view('follow_ups.create', compact('patients','doctors','records'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'doctor_id' => 'nullable|exists:users,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'date' => 'required|date',
            'time' => 'nullable',
            'note' => 'nullable|string',
            'status' => 'nullable|in:upcoming,completed,cancelled',
        ]);

        FollowUp::create($data);
        return redirect()->route('follow_ups.index')->with('success','Follow-up created.');
    }

    public function edit(FollowUp $followUp)
    {
        $patients = User::whereHas('roles', fn($q)=>$q->where('name','patient'))->get();
        $doctors = User::whereHas('roles', fn($q)=>$q->where('name','doctor'))->get();
        $records = MedicalRecord::all();
        return view('follow_ups.edit', compact('followUp','patients','doctors','records'));
    }

    public function update(Request $request, FollowUp $followUp)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'doctor_id' => 'nullable|exists:users,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'date' => 'required|date',
            'time' => 'nullable',
            'note' => 'nullable|string',
            'status' => 'nullable|in:upcoming,completed,cancelled',
        ]);

        $followUp->update($data);
        return redirect()->route('follow_ups.index')->with('success','Follow-up updated.');
    }

    public function destroy(FollowUp $followUp)
    {
        $followUp->delete();
        return redirect()->route('follow_ups.index')->with('success','Follow-up deleted.');
    }
}
