<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;

class MedicineCategoryController extends Controller
{
    public function index()
    {
        $categories = MedicineCategory::latest()->paginate(10);
        return view('medicines.medicine_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('medicines.medicine_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:medicine_categories,name',
        ]);

        MedicineCategory::create($request->all());

        return redirect()
            ->route('medicine_categories.index')
            ->with('success', 'Thêm danh mục thuốc thành công');
    }

    public function edit(MedicineCategory $medicineCategory)
    {
        return view('medicines.medicine_categories.edit', compact('medicineCategory'));
    }

    public function update(Request $request, MedicineCategory $medicineCategory)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:medicine_categories,name,' . $medicineCategory->id,
        ]);

        $medicineCategory->update($request->all());

        return redirect()
            ->route('medicine_categories.index')
            ->with('success', 'Cập nhật danh mục thành công');
    }

    public function destroy(MedicineCategory $medicineCategory)
    {
        $medicineCategory->delete();

        return redirect()
            ->route('medicine_categories.index')
            ->with('success', 'Xóa danh mục thành công');
    }
}
