<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
  public function index()
    {
        $invoices = Invoice::with('patient', 'appointment', 'medicalRecord', 'items')->get();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $patients = User::whereHas('roles', fn($q) => $q->where('name','patient'))->get();
        $appointments = Appointment::all();
        $records = MedicalRecord::all();
        return view('invoices.create', compact('patients','appointments','records'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:invoices,code',
            'user_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'total' => 'nullable|numeric',
            'status' => 'nullable|in:unpaid,paid,refunded',
            'payment_method' => 'nullable|in:cash,bank,momo,vnpay',
            'paid_at' => 'nullable|date',
            'refund_amount' => 'nullable|numeric',
            'note' => 'nullable|string',
            'created_by' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
        ]);

        Invoice::create($data);
        return redirect()->route('invoices.index')->with('success','Invoice created.');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $patients = User::whereHas('roles', fn($q) => $q->where('name','patient'))->get();
        $appointments = Appointment::all();
        $records = MedicalRecord::all();
        return view('invoices.edit', compact('invoice','patients','appointments','records'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'code' => "required|unique:invoices,code,$invoice->id",
            'user_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'total' => 'nullable|numeric',
            'status' => 'nullable|in:unpaid,paid,refunded',
            'payment_method' => 'nullable|in:cash,bank,momo,vnpay',
            'paid_at' => 'nullable|date',
            'refund_amount' => 'nullable|numeric',
            'note' => 'nullable|string',
            'created_by' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
        ]);

        $invoice->update($data);
        return redirect()->route('invoices.index')->with('success','Invoice updated.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success','Invoice deleted.');
    }
}
