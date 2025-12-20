<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicineUnit;
use Illuminate\Http\Request;

class MedicineUnitController extends Controller
{
   public function index()
    {
        $units = MedicineUnit::latest()->paginate(10);
        return view('medicines.medicine_units.index', compact('units'));
    }

    public function create()
    {
        return view('medicines.medicine_units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:medicine_units,name',
        ]);

        MedicineUnit::create($request->all());

        return redirect()
            ->route('medicine_units.index')
            ->with('success', 'Thêm đơn vị thuốc thành công');
    }

    public function edit(MedicineUnit $medicineUnit)
    {
        return view('medicines.medicine_units.edit', compact('medicineUnit'));
    }

    public function update(Request $request, MedicineUnit $medicineUnit)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:medicine_units,name,' . $medicineUnit->id,
        ]);

        $medicineUnit->update($request->all());

        return redirect()
            ->route('medicine_units.index')
            ->with('success', 'Cập nhật đơn vị thuốc thành công');
    }

    public function destroy(MedicineUnit $medicineUnit)
    {
        $medicineUnit->delete();

        return redirect()
            ->route('medicine_units.index')
            ->with('success', 'Xóa đơn vị thuốc thành công');
    }
}
