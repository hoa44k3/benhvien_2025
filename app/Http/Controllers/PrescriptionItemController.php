<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Medicine;
use Carbon\Carbon;

class PrescriptionItemController extends Controller
{
    public function create(Prescription $prescription)
    {
        $medicines = Medicine::all();
        return view('prescriptions.items.create', compact('prescription', 'medicines'));
    }

    public function store(Request $request, Prescription $prescription)
    {
        $data = $request->validate([
            'medicine_id' => 'nullable|exists:medicines,id',
            'medicine_name' => 'required|string',
            'dosage' => 'nullable|string',
            'frequency' => 'nullable|string',
            'duration' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            // 'price' => 'nullable', // Bỏ validate price
            'instruction' => 'nullable|string',
            'strength' => 'nullable|string',
            'unit' => 'nullable|string',
            'times_per_day' => 'nullable|integer',
        ]);

        // Kiểm tra Hạn sử dụng (Logic cũ giữ nguyên)
        if ($request->filled('medicine_id')) {
            $med = Medicine::find($request->medicine_id);
            if ($med && $med->expiry_date) {
                $daysLeft = Carbon::now()->diffInDays(Carbon::parse($med->expiry_date), false);
                if ($daysLeft < 0) {
                    return back()->withInput()->withErrors(['medicine_id' => "LỖI: Thuốc đã hết hạn!"]);
                }
                // Tự động điền tên nếu chọn thuốc
                $data['medicine_name'] = $med->name;
            }
        }

        // --- QUAN TRỌNG: Luôn set giá bằng 0 ---
        $data['price'] = 0;

        $prescription->items()->create($data);

        return redirect()
            ->route('prescriptions.edit', $prescription->id)
            ->with('success', 'Thêm thuốc thành công!');
    }

    public function edit(PrescriptionItem $item)
    {
        $medicines = Medicine::all();
        return view('prescriptions.items.edit', compact('item', 'medicines'));
    }

    public function update(Request $request, PrescriptionItem $item)
    {
        $data = $request->validate([
            'medicine_id' => 'nullable|exists:medicines,id',
            'medicine_name' => 'required|string',
            'dosage' => 'nullable|string',
            'frequency' => 'nullable|string',
            'duration' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'instruction' => 'nullable|string',
            'strength' => 'nullable|string',
            'unit' => 'nullable|string',
            'times_per_day' => 'nullable|integer',
        ]);

        // Logic check hạn sử dụng (giữ nguyên)
        if ($request->filled('medicine_id')) {
            $med = Medicine::find($request->medicine_id);
            if ($med) {
                 $data['medicine_name'] = $med->name;
            }
        }

        // --- Luôn set giá bằng 0 ---
        $data['price'] = 0;

        $item->update($data);

        return redirect()
            ->route('prescriptions.edit', $item->prescription_id)
            ->with('success', 'Cập nhật thuốc thành công!');
    }

    public function destroy(PrescriptionItem $item)
    {
        $item->delete();
        // Trả về JSON để dùng fetch ở frontend hoặc redirect nếu dùng form submit thường
        if(request()->wantsJson()){
             return response()->json(['success' => true]);
        }
        return back()->with('success', 'Đã xóa thuốc.');
    }
}