<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Medicine;

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

        // Auto fill nếu có medicine_id
        if ($request->filled('medicine_id')) {
            $med = Medicine::find($request->medicine_id);
            if ($med) {
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

        // Nếu chọn thuốc có sẵn
        if ($request->filled('medicine_id')) {
            $med = Medicine::find($request->medicine_id);
            if ($med) {
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
