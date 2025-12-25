<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Thư viện PDF

class InvoiceController extends Controller
{
    // 1. Danh sách hóa đơn
    public function index()
    {
        $invoices = Invoice::with(['user', 'appointment', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    // 2. Form tạo thủ công (ít dùng, chủ yếu tạo từ lịch hẹn)
    public function create()
    {
        $users = User::where('status', 'active')->get(); 
        return view('invoices.create', compact('users'));
    }

    // 3. Lưu hóa đơn thủ công
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:unpaid,paid,refunded',
            'items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'code' => 'HD-' . strtoupper(Str::random(8)),
                'user_id' => $request->user_id,
                'total' => $request->total_amount,
                'status' => $request->status,
                'note' => $request->note,
                'created_by' => auth()->id(),
                'issued_date' => now(),
            ]);

            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_name' => $item['description'],
                    'item_type' => 'service',
                    'quantity' => $item['quantity'],
                    'price' => $item['unit_price'],
                    'total' => $item['quantity'] * $item['unit_price']
                ]);
            }

            DB::commit();
            return redirect()->route('invoices.show', $invoice->id)->with('success', 'Tạo hóa đơn thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    // 4. Xem chi tiết
    public function show(Invoice $invoice)
    {
        $invoice->load(['user', 'items', 'appointment', 'createdBy']); 
        return view('invoices.show', compact('invoice'));
    }

    // 5. Form sửa
    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    // 6. Cập nhật trạng thái
    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'status' => 'required|in:unpaid,paid,refunded',
            'payment_method' => 'nullable|in:cash,bank,momo,vnpay',
            'note' => 'nullable|string',
        ]);

        if ($data['status'] == 'paid' && $invoice->status != 'paid') {
            $data['paid_at'] = now();
        }

        $invoice->update($data);
        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Cập nhật thành công.');
    }

    // 7. Xóa hóa đơn
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Đã xóa hóa đơn.');
    }

    // 8. LOGIC QUAN TRỌNG: TẠO HÓA ĐƠN TỪ LỊCH HẸN (THU PHÍ KHÁM)
    public function createFromAppointment($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        // Check trùng
        $existing = Invoice::where('appointment_id', $appointmentId)->first();
        if ($existing) {
            return redirect()->route('invoices.show', $existing->id)->with('warning', 'Hóa đơn cho lịch hẹn này đã tồn tại.');
        }

        // Lấy phí khám (Mặc định 200k hoặc lấy từ DB Bác sĩ)
        $fee = 200000; 
        // Code mở rộng nếu muốn lấy đúng giá bác sĩ:
        // $doctorSite = \App\Models\DoctorSite::where('user_id', $appointment->doctor_id)->first();
        // if($doctorSite && $doctorSite->department) $fee = $doctorSite->department->fee;

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'code' => 'HD-KB-' . strtoupper(Str::random(6)),
                'user_id' => $appointment->user_id ?? 0, // ID bệnh nhân (nếu có)
                'appointment_id' => $appointment->id,
                'total' => $fee,
                'status' => 'unpaid',
                'created_by' => auth()->id(),
                'issued_date' => now(),
                'note' => 'Thu phí khám bệnh ngày ' . \Carbon\Carbon::parse($appointment->date)->format('d/m/Y')
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_name' => 'Phí khám & Tư vấn chuyên khoa',
                'item_type' => 'service',
                'quantity' => 1,
                'price' => $fee,
                'total' => $fee,
            ]);

            DB::commit();
            return redirect()->route('invoices.show', $invoice->id)->with('success', 'Đã tạo hóa đơn phí khám.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    // 9. XUẤT FILE PDF
    public function print($id)
    {
        $invoice = Invoice::with(['user', 'items'])->findOrFail($id);
        
        // Load view pdf và truyền biến data
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        // Stream (Mở xem trước) hoặc Download
        return $pdf->stream('Hoa-don-' . $invoice->code . '.pdf');
    }
}