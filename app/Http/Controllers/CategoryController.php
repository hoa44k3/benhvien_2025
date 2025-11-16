<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class CategoryController extends Controller
{
     // Hiển thị danh sách
    public function index()
    {
        $categories = Category::orderByDesc('id')->paginate(15);
        return view('categories.index', compact('categories'));
    }

    // Form tạo
    public function create()
    {
        return view('categories.create');
    }

    // Lưu mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'nullable|in:0,1',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image_path'] = $path;
        }

        $validated['slug'] = Str::slug($validated['name'] . '-' . uniqid());
        $validated['status'] = $request->input('status', 1);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công.');
    }

    // Form chỉnh sửa
    // public function edit(Category $category)
    // {
    //     return view('categories.edit', compact('category'));
    // }
    public function edit($id)
{
    $category = Category::findOrFail($id);
    return view('categories.edit', compact('category'));
}

    // Cập nhật
    public function update(Request $request, $id)
    {
        // $validated = $request->validate([
        //     'name' => 'required|string|max:150',
        //     'description' => 'nullable|string',
        //     'image' => 'nullable|image|max:2048',
        //     'status' => 'nullable|in:0,1',
        // ]);

        // if ($request->hasFile('image')) {
        //     // xóa file cũ nếu có
        //     if ($category->image_path && Storage::disk('public')->exists($category->image_path)) {
        //         Storage::disk('public')->delete($category->image_path);
        //     }
        //     $path = $request->file('image')->store('categories', 'public');
        //     $validated['image_path'] = $path;
        // }

        // // cập nhật slug nếu tên thay đổi
        // if ($category->name !== $validated['name']) {
        //     $validated['slug'] = Str::slug($validated['name'] . '-' . uniqid());
        // }

        // $validated['status'] = $request->input('status', $category->status);

        // $category->update($validated);

        // return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công.');
         $validated = $request->validate([
        'name' => 'required|string|max:150',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
        'status' => 'nullable|in:0,1',
    ]);

    // tìm category theo id
    $category = Category::findOrFail($id);

    // nếu có ảnh mới => xóa ảnh cũ + lưu ảnh mới
    if ($request->hasFile('image')) {
        if ($category->image_path && Storage::disk('public')->exists($category->image_path)) {
            Storage::disk('public')->delete($category->image_path);
        }
        $validated['image_path'] = $request->file('image')->store('categories', 'public');
    }

    // nếu tên thay đổi => cập nhật slug
    if ($category->name !== $validated['name']) {
        $validated['slug'] = Str::slug($validated['name'] . '-' . uniqid());
    }

    // nếu không có status gửi lên thì giữ nguyên
    $validated['status'] = $request->input('status', $category->status);

    // cập nhật dữ liệu
    $category->update($validated);

    return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công.');
    }

    // Xóa
    public function destroy(Category $category)
    {
        if ($category->image_path && Storage::disk('public')->exists($category->image_path)) {
            Storage::disk('public')->delete($category->image_path);
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Đã xóa danh mục.');
    }
}
