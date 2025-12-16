<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; // Thêm
use App\Mail\InvoicePaid; // Thêm
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = \App\Models\Invoice::with(['user', 'medicalRecord', 'items'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $users = User::all(); // Nên lọc theo role patient
        $records = MedicalRecord::all();
        return view('invoices.create', compact('users', 'records'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
            'status' => 'required|in:unpaid,paid,refunded',
            'note' => 'nullable|string',
        ]);

        $data['code'] = 'HD-' . strtoupper(Str::random(8));
        $data['created_by'] = auth()->id();
        $data['issued_date'] = now();

        Invoice::create($data);

        return redirect()->route('invoices.index')->with('success', 'Tạo hóa đơn thủ công thành công.');
    }

    public function show(Invoice $invoice)
    {
        // Load chi tiết hóa đơn (items) để hiển thị bảng giá
        $invoice->load(['user', 'items', 'medicalRecord', 'createdBy']); 
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $users = User::all();
        return view('invoices.edit', compact('invoice', 'users'));
    }

    // public function update(Request $request, Invoice $invoice)
    // {
    //     $data = $request->validate([
    //         'status' => 'required|in:unpaid,paid,refunded',
    //         'payment_method' => 'nullable|in:cash,bank,momo,vnpay',
    //         'note' => 'nullable|string',
    //     ]);

    //     // Nếu chuyển sang đã thanh toán thì cập nhật ngày trả
    //     if ($data['status'] == 'paid' && $invoice->status != 'paid') {
    //         $data['paid_at'] = now();
    //     }

    //     $invoice->update($data);

    //     return redirect()->route('invoices.show', $invoice->id)->with('success', 'Cập nhật trạng thái hóa đơn thành công.');
    // }
    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'status' => 'required|in:unpaid,paid,refunded',
            'payment_method' => 'nullable|in:cash,bank,momo,vnpay',
            'note' => 'nullable|string',
        ]);

        // Logic gửi mail khi Admin xác nhận thanh toán
        // Nếu chuyển từ TRẠNG THÁI KHÁC sang PAID
        if ($data['status'] == 'paid' && $invoice->status != 'paid') {
            $data['paid_at'] = now();
            
            // Gửi mail cho bệnh nhân
            if ($invoice->user && $invoice->user->email) {
                try {
                    Mail::to($invoice->user->email)->send(new InvoicePaid($invoice));
                } catch (\Exception $e) {
                    Log::error('Admin update invoice - Mail error: ' . $e->getMessage());
                }
            }
        }

        $invoice->update($data);

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Cập nhật trạng thái hóa đơn thành công.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Đã xóa hóa đơn.');
    }

    /**
     * Logic tạo hóa đơn riêng từ Đơn thuốc (Nếu dùng nút "Tạo hóa đơn" ở trang đơn thuốc)
     */
    public function createFromPrescription($prescriptionId)
    {
        $prescription = Prescription::with('items')->findOrFail($prescriptionId);

        // Tính tiền thuốc
        $totalAmount = $prescription->items->sum(fn($item) => ($item->price ?? 0) * ($item->quantity ?? 1));

        // Tạo Hóa đơn
        $invoice = Invoice::create([
            'code' => 'HD-DT-' . strtoupper(Str::random(6)),
            'user_id' => $prescription->patient_id,
            'prescription_id' => $prescription->id,
            'total' => $totalAmount,
            'status' => 'unpaid',
            'created_by' => auth()->id(),
            'issued_date' => now(),
        ]);

        // Copy thuốc sang chi tiết hóa đơn
        foreach ($prescription->items as $item) {
            DB::table('invoice_items')->insert([
                'invoice_id' => $invoice->id,
                'item_name' => $item->medicine_name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => ($item->price * $item->quantity),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Đã tạo hóa đơn từ đơn thuốc.');
    }
}