<?php

namespace App\Http\Controllers;

use App\Helpers\AuditHelper;
use App\Models\Staff;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Hiển thị danh sách nhân viên
     */
    public function index()
    {
        $staff = Staff::with(['department', 'role', 'user'])->paginate(10);
        return view('staff.index', compact('staff'));
    }

    /**
     * Form thêm nhân viên
     */
    public function create()
    {
        $departments = Department::all();
        $roles = Role::all();
        return view('staff.create', compact('departments', 'roles'));
    }

    /**
     * Lưu nhân viên mới (tạo cả tài khoản user)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'staff_code' => 'required|string|max:20|unique:staff,staff_code',
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'position' => 'required|string|max:100',
                'department_id' => 'nullable|exists:departments,id',
                'role_id' => 'nullable|exists:roles,id',
                'phone' => 'nullable|string|max:20',
                'experience_years' => 'nullable|integer|min:0',
                'rating' => 'nullable|numeric|min:0|max:5',
                'status' => 'required|in:Hoạt động,Nghỉ phép,Nghỉ việc',
                'password' => 'nullable|string|min:6',
            ]);

            DB::beginTransaction();

            // 🔹 Tạo tài khoản user tương ứng
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? '123456'),
                'status' => 'Hoạt động',
            ]);

            // Gán role cho user
            if (!empty($validated['role_id'])) {
                $role = Role::find($validated['role_id']);
                if ($role) {
                    $user->roles()->sync([$role->id]);
                }
            }

            // 🔹 Tạo staff và liên kết user_id
            $validated['user_id'] = $user->id;
            Staff::create($validated);

            DB::commit();

            AuditHelper::log('Tạo nhân viên mới', $request->name, 'Thành công');
            return redirect()->route('staff.index')->with('success', 'Thêm nhân viên thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            AuditHelper::log('Tạo nhân viên mới', $request->name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', 'Lỗi khi thêm nhân viên: ' . $e->getMessage());
        }
    }

    /**
     * Form chỉnh sửa nhân viên
     */
    public function edit(Staff $staff)
    {
        $departments = Department::all();
        $roles = Role::all();
        return view('staff.edit', compact('staff', 'departments', 'roles'));
    }

    /**
     * Cập nhật thông tin nhân viên + user liên kết
     */
    public function update(Request $request, Staff $staff)
    {
        try {
            $validated = $request->validate([
                'staff_code' => 'required|string|max:20|unique:staff,staff_code,' . $staff->id,
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email,' . $staff->user_id,
                'position' => 'required|string|max:100',
                'department_id' => 'nullable|exists:departments,id',
                'role_id' => 'nullable|exists:roles,id',
                'phone' => 'nullable|string|max:20',
                'experience_years' => 'nullable|integer|min:0',
                'rating' => 'nullable|numeric|min:0|max:5',
                'status' => 'required|in:Hoạt động,Nghỉ phép,Nghỉ việc',
                'password' => 'nullable|string|min:6',
            ]);

            DB::beginTransaction();

            // Cập nhật staff
            $staff->update($validated);

            // Cập nhật user liên kết
            $user = $staff->user;
            if ($user) {
                $user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'status' => $validated['status'],
                ]);

                if (!empty($validated['password'])) {
                    $user->password = Hash::make($validated['password']);
                    $user->save();
                }

                if (!empty($validated['role_id'])) {
                    $user->roles()->sync([$validated['role_id']]);
                }
            }

            DB::commit();

            AuditHelper::log('Cập nhật thông tin nhân viên', $staff->name, 'Thành công');
            return redirect()->route('staff.index')->with('success', 'Cập nhật thông tin nhân viên thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            AuditHelper::log('Cập nhật thông tin nhân viên', $staff->name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', 'Lỗi khi cập nhật nhân viên: ' . $e->getMessage());
        }
    }

    /**
     * Xóa nhân viên và user liên kết
     */
    public function destroy(Staff $staff)
    {
        try {
            DB::beginTransaction();

            if ($staff->user) {
                $staff->user->delete();
            }

            $staff->delete();

            DB::commit();

            AuditHelper::log('Xóa nhân viên', $staff->name, 'Thành công');
            return redirect()->route('staff.index')->with('success', 'Xóa nhân viên thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            AuditHelper::log('Xóa nhân viên', $staff->name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', 'Lỗi khi xóa nhân viên: ' . $e->getMessage());
        }
    }
}
