<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Medicine;
use Carbon\Carbon;

class PrescriptionItemController extends Controller
{
    // Tạo item
    public function create(Prescription $prescription)
    {
        $medicines = Medicine::all();
        return view('prescriptions.items.create', compact('prescription', 'medicines'));
    }

    // Lưu item mới
    public function store(Request $request, Prescription $prescription)
    {
        $data = $request->validate([
            'medicine_id' => 'nullable|exists:medicines,id',
            'medicine_name' => 'required|string',
            'dosage' => 'nullable|string',
            'frequency' => 'nullable|string',
            'duration' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'nullable|numeric',
            'instruction' => 'nullable|string',
            'strength' => 'nullable|string',
        'unit' => 'nullable|string',
        'times_per_day' => 'nullable|integer',
        ]);

    // LOGIC MỚI: Kiểm tra hạn sử dụng trước khi thêm
    if ($request->filled('medicine_id')) {
        $med = Medicine::find($request->medicine_id);
        
        if ($med && $med->expiry_date) {
            $expiry = Carbon::parse($med->expiry_date);
            $now = Carbon::now();
            $daysLeft = $now->diffInDays($expiry, false); // false để lấy số âm nếu đã qua

            // 1. Chặn nếu đã hết hạn
            if ($daysLeft < 0) {
                return back()
                    ->withInput()
                    ->withErrors(['medicine_id' => "LỖI: Thuốc \"{$med->name}\" đã hết hạn sử dụng. Không thể kê đơn!"]);
            }

            // 2. Chặn nếu sắp hết hạn (ví dụ dưới 60 ngày)
            // Bạn có thể chỉnh số 60 thành số ngày quy định của bệnh viện
            if ($daysLeft < 60) {
                return back()
                    ->withInput()
                    ->withErrors(['medicine_id' => "CẢNH BÁO: Thuốc \"{$med->name}\" là thuốc cận date (còn {$daysLeft} ngày). Để đảm bảo an toàn, vui lòng chọn lô thuốc khác!"]);
            }
            
            // Nếu OK
            $data['medicine_name'] = $med->name;
            $data['price'] = $med->price;
        }
    }


        $prescription->items()->create($data);

        return redirect()
            ->route('prescriptions.edit', $prescription->id)
            ->with('success', 'Thêm thuốc thành công!');
    }

    // Form edit
    public function edit(PrescriptionItem $item)
    {
        $medicines = Medicine::all();
        return view('prescriptions.items.edit', compact('item', 'medicines'));
    }

    // Update item
    public function update(Request $request, PrescriptionItem $item)
    {
        $data = $request->validate([
            'medicine_id' => 'nullable|exists:medicines,id',
            'medicine_name' => 'required|string',
            'dosage' => 'nullable|string',
            'frequency' => 'nullable|string',
            'duration' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'nullable|numeric',
            'instruction' => 'nullable|string',
            'strength' => 'nullable|string',
        'unit' => 'nullable|string',
        'times_per_day' => 'nullable|integer',
        ]);

       if ($request->filled('medicine_id')) {
        $med = Medicine::find($request->medicine_id);
        
        if ($med && $med->expiry_date) {
            $expiry = Carbon::parse($med->expiry_date);
            $now = Carbon::now();
            $daysLeft = $now->diffInDays($expiry, false); // false để lấy số âm nếu đã qua

            // 1. Chặn nếu đã hết hạn
            if ($daysLeft < 0) {
                return back()
                    ->withInput()
                    ->withErrors(['medicine_id' => "LỖI: Thuốc \"{$med->name}\" đã hết hạn sử dụng. Không thể kê đơn!"]);
            }

            // 2. Chặn nếu sắp hết hạn (ví dụ dưới 60 ngày)
            // Bạn có thể chỉnh số 60 thành số ngày quy định của bệnh viện
            if ($daysLeft < 60) {
                return back()
                    ->withInput()
                    ->withErrors(['medicine_id' => "CẢNH BÁO: Thuốc \"{$med->name}\" là thuốc cận date (còn {$daysLeft} ngày). Để đảm bảo an toàn, vui lòng chọn lô thuốc khác!"]);
            }
            
            // Nếu OK
            $data['medicine_name'] = $med->name;
            $data['price'] = $med->price;
        }
    }

        $item->update($data);

        return redirect()
            ->route('prescriptions.edit', $item->prescription_id)
            ->with('success', 'Cập nhật thuốc thành công!');
    }

    // Xóa thuốc
    public function destroy(PrescriptionItem $item)
    {
        $item->delete();

        return response()->json(['success' => true]);
    }
}
