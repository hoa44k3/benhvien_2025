<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use App\Models\Department;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
   public function index()
    {
        $services = Service::with(['category', 'department'])->paginate(10);
        return view('services.index', compact('services'));
    }

    public function create()
    {
        $categories = Category::all();
        $departments = Department::all();
        return view('services.create', compact('categories', 'departments'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:150',
        'fee' => 'required|numeric',
        'duration' => 'required|integer',
        'status' => 'required|in:0,1',
        'category_id' => 'nullable|exists:categories,id',
        'department_id' => 'nullable|exists:departments,id',
        'description' => 'nullable|string',
        'content' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);
// Chỉ lấy các trường cần thiết
    $data = $request->only([
        'name', 'description', 'content', 'fee', 'duration', 'status', 'category_id', 'department_id'
    ]);
        $data['duration'] = $request->duration ?? 0;
    // Lưu file ảnh nếu có
    if($request->hasFile('image')){
        $data['image'] = $request->file('image')->store('services', 'public');
    }
    Service::create($data);

    return redirect()->route('services.index')->with('success', 'Dịch vụ đã được thêm thành công!');
}

    public function edit(Service $service)
    {
        $categories = Category::all();
        $departments = Department::all();
        return view('services.edit', compact('service', 'categories', 'departments'));
    }
    public function update(Request $request, Service $service)
{
    $request->validate([
        'name' => 'required|string|max:150',
        'fee' => 'required|numeric',
        'duration' => 'required|integer',
        'status' => 'required|in:0,1',
        'category_id' => 'nullable|exists:categories,id',
        'department_id' => 'nullable|exists:departments,id',
        'description' => 'nullable|string',
        'content' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    // $data = $request->all();

    // if($request->hasFile('image')){
    //     $data['image'] = $request->file('image')->store('services', 'public');
    // }
     $data = $request->only([
        'name', 'description', 'content', 'fee', 'duration', 'status', 'category_id', 'department_id'
    ]);
    $data['duration'] = $request->duration ?? 0;
    // Nếu upload file mới, thay ảnh cũ
    if($request->hasFile('image')){
        // Xoá file cũ nếu muốn
        if($service->image && Storage::disk('public')->exists($service->image)){
            Storage::disk('public')->delete($service->image);
        }
        $data['image'] = $request->file('image')->store('services', 'public');
    }


    $service->update($data);

    return redirect()->route('services.index')->with('success', 'Dịch vụ đã được cập nhật!');
}
public function show(Service $service)
{
    $service->load(['category', 'department']);
    return view('services.show', compact('service'));
}

    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['success' => true]);
    }
}
