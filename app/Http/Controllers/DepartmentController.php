<?php

namespace App\Http\Controllers;

use App\Helpers\AuditHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
class DepartmentController extends Controller
{
    public function index(Request $request)
    {
            $doctors = User::whereHas('roles', function($q){
            $q->where('name', 'doctor');
        })->get();

        // $departments = Department::all();
         $query = Department::query();

        // tìm kiếm theo tên hoặc mã
        if ($q = $request->input('q')) {
            $query->where(function($q2) use ($q) {
                $q2->where('name', 'like', '%' . $q . '%')
                   ->orWhere('code', 'like', '%' . $q . '%');
            });
        }

        // lọc theo trạng thái
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // sắp xếp mặc định theo created_at desc
        $departments = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('departments.index', compact('departments','doctors'));
    }
    private function getDoctors()
    {
        return User::whereHas('roles', function($q){
            $q->where('name', 'doctor');
        })->get();
    }
    public function create()
    {
        $doctors = $this->getDoctors();
        return view('departments.create', compact('doctors'));
    }
 
    public function store(Request $request)
    {
        // dd($request->all());

         $request->validate([
            'code' => 'required|string|max:20|unique:departments,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
             'user_id' => 'nullable|exists:users,id',
            'num_doctors' => 'nullable|integer|min:0',
            'num_nurses' => 'nullable|integer|min:0',
            'num_rooms' => 'nullable|integer|min:0',
            'fee' => 'nullable|numeric|min:0',
            'status' => ['nullable', Rule::in(['active','inactive'])],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'icon' => 'nullable|string|max:50',
        ]);

        try {
            $data = $request->only([
                'code','name','description','user_id',
                'num_doctors','num_nurses','num_rooms',
                'fee','status'
            ]);

            // đảm bảo defaults
            $data['num_doctors'] = $data['num_doctors'] ?? 0;
            $data['num_nurses'] = $data['num_nurses'] ?? 0;
            $data['num_rooms'] = $data['num_rooms'] ?? 0;
            $data['fee'] = $data['fee'] ?? 0;
            $data['status'] = $data['status'] ?? 'active';

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('departments', 'public');
            }

            $department = Department::create($data);

            AuditHelper::log('Tạo chuyên khoa', $department->name ?? $department->code, 'Thành công');

            return redirect()->route('departments.index')->with('success', 'Thêm chuyên khoa thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo chuyên khoa: '.$e->getMessage(), ['exception' => $e]);
            AuditHelper::log('Tạo chuyên khoa', $request->input('name') ?? $request->input('code', 'Không rõ'), 'Thất bại');

            return redirect()->back()->withInput()->with('error', 'Lỗi khi thêm chuyên khoa: ' . $e->getMessage());
        }
       
    }

    public function show(Department $department)
    {
        return view('departments.show', compact('department'));
    }


    public function edit(Department $department)
    {
        $doctors = $this->getDoctors();
        return view('departments.edit', compact('department','doctors'));
    }

    public function update(Request $request, Department $department)
    {
        // dd($request->all());

        $request->validate([
            'code' => ['required','string','max:20', Rule::unique('departments','code')->ignore($department->id)],
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
             'user_id' => 'nullable|exists:users,id',
            'num_doctors' => 'nullable|integer|min:0',
            'num_nurses' => 'nullable|integer|min:0',
            'num_rooms' => 'nullable|integer|min:0',
            'fee' => 'nullable|numeric|min:0',
            'status' => ['nullable', Rule::in(['active','inactive'])],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'icon' => 'nullable|string|max:50',
        ]);

        try {
            $data = $request->only([
                'code','name','description','user_id',
                'num_doctors','num_nurses','num_rooms',
                'fee','status'
            ]);

            // defaults
            $data['num_doctors'] = $data['num_doctors'] ?? $department->num_doctors ?? 0;
            $data['num_nurses'] = $data['num_nurses'] ?? $department->num_nurses ?? 0;
            $data['num_rooms'] = $data['num_rooms'] ?? $department->num_rooms ?? 0;
            $data['fee'] = $data['fee'] ?? $department->fee ?? 0;
            $data['status'] = $data['status'] ?? $department->status ?? 'active';

            if ($request->hasFile('image')) {
                // xóa ảnh cũ nếu có
                if ($department->image) {
                    Storage::disk('public')->delete($department->image);
                }
                $data['image'] = $request->file('image')->store('departments', 'public');
            }

            $department->update($data);

            AuditHelper::log('Cập nhật chuyên khoa', $department->name, 'Thành công');

            return redirect()->route('departments.index')->with('success', 'Cập nhật chuyên khoa thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật chuyên khoa: '.$e->getMessage(), ['department_id' => $department->id, 'exception' => $e]);
            AuditHelper::log('Cập nhật chuyên khoa', $department->name ?? $department->code, 'Thất bại');
            return redirect()->back()->withInput()->with('error', 'Lỗi khi cập nhật chuyên khoa: ' . $e->getMessage());
        }
    }

    public function destroy(Department $department)
    {
        try {
            if ($department->image) {
                Storage::disk('public')->delete($department->image);
            }
            $department->delete();

            AuditHelper::log('Xóa chuyên khoa', $department->name ?? $department->code, 'Thành công');

            return redirect()->route('departments.index')->with('success', 'Xóa chuyên khoa thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa chuyên khoa: '.$e->getMessage(), ['department_id' => $department->id, 'exception' => $e]);
            AuditHelper::log('Xóa chuyên khoa', $department->name ?? $department->code, 'Thất bại');
            return redirect()->back()->with('error', 'Lỗi khi xóa chuyên khoa: ' . $e->getMessage());
        }
    }
}
