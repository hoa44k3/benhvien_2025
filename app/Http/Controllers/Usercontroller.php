<?php

namespace App\Http\Controllers;

use App\Helpers\AuditHelper;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('created_at', 'desc')->get();
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }
     public function store(Request $request)
    {
        

        try {
            $request->validate([
                'patient_code' => 'nullable|string|max:20|unique:users,patient_code',
                'name' => 'required|string|max:255',
                'age' => 'nullable|integer|min:0',
                'phone' => 'nullable|string|max:15',
                'last_visit' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:50',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'address' => 'nullable|string|max:255',
                'cccd' => 'nullable|string|max:20|unique:users,cccd',
                'occupation' => 'nullable|string|max:100',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'gender' => 'nullable|string|max:10',
                'date_of_birth' => 'nullable|date',
            ]);

            $user = new User();
            $user->patient_code = $request->patient_code;
            $user->name = $request->name;
            $user->age = $request->age;
            $user->phone = $request->phone;
            $user->last_visit = $request->last_visit;
            $user->status = $request->status ?? 'Hoạt động';
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->address = $request->address;
            $user->cccd = $request->cccd;
            $user->occupation = $request->occupation;
            $user->gender = $request->gender;
            $user->date_of_birth = $request->date_of_birth;
            $user->is_active = $request->has('is_active') ? $request->is_active : true;

            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            }

            $user->save();
            // Gắn role cho user
                    $user->roles()->sync($request->role_ids ?? []);

                    // Nếu user là bác sĩ, tạo record trong doctor_sites
                    if(in_array('doctor', $request->role_ids ?? [])) { // hoặc kiểm tra bằng role name
                        \App\Models\DoctorSite::create([
                            'user_id' => $user->id,
                            'department_id' => null,
                            'specialty' => null,
                            'bio' => null,
                            'rating' => 0,
                            'reviews_count' => 0,
                            'status' => 1
                        ]);
                    }
            // 🔹 Ghi log thành công
            AuditHelper::log('Tạo tài khoản mới', $user->name, 'Thành công');

            return redirect()->route('users.index')->with('success', 'Thêm người dùng thành công!');
        } catch (\Exception $e) {
            // 🔹 Ghi log thất bại
            AuditHelper::log('Tạo tài khoản mới', $request->name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi thêm người dùng.');
        }
    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Lấy danh sách role để hiển thị select
        return view('users.edit', compact('user', 'roles'));
    }
 public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        try {
            $request->validate([
                'patient_code' => 'nullable|string|max:20|unique:users,patient_code,' . $id,
                'name' => 'required|string|max:255',
                'age' => 'nullable|integer|min:0',
                'phone' => 'nullable|string|max:15',
                'last_visit' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:50',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:6',
                'address' => 'nullable|string|max:255',
                'cccd' => 'nullable|string|max:20|unique:users,cccd,' . $id,
                'occupation' => 'nullable|string|max:100',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'gender' => 'nullable|string|max:10',
                'date_of_birth' => 'nullable|date',
            ]);

            $user->patient_code = $request->patient_code;
            $user->name = $request->name;
            $user->age = $request->age;
            $user->phone = $request->phone;
            $user->last_visit = $request->last_visit;
            $user->status = $request->status ?? 'Hoạt động';
            $user->email = $request->email;
            $user->address = $request->address;
            $user->cccd = $request->cccd;
            $user->occupation = $request->occupation;
            $user->gender = $request->gender;
            $user->date_of_birth = $request->date_of_birth;
            $user->is_active = $request->has('is_active') ? $request->is_active : true;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('avatar')) {
                if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                    Storage::delete('public/' . $user->avatar);
                }
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            }

            $user->roles()->sync($request->role_ids ?? []);
            // Lấy tên các role vừa gán
        $roles = Role::whereIn('id', $request->role_ids ?? [])->pluck('name')->toArray();
        
        if (in_array('doctor', $roles)) {
            // Kiểm tra xem đã có hồ sơ chưa, chưa có thì tạo
            $exists = \App\Models\DoctorSite::where('user_id', $user->id)->exists();
            if (!$exists) {
                \App\Models\DoctorSite::create([
                    'user_id' => $user->id,
                    'status' => 1,
                    'rating' => 0,
                    'reviews_count' => 0
                ]);
            }
        }
            $user->save();

            // 🔹 Ghi log thành công
            AuditHelper::log('Cập nhật thông tin người dùng', $user->name, 'Thành công');

            return redirect()->route('users.index')->with('success', 'Cập nhật người dùng thành công!');
        } catch (\Exception $e) {
            // 🔹 Ghi log thất bại
            AuditHelper::log('Cập nhật thông tin người dùng', $user->name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật người dùng.');
        }
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        try {
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }
            $name = $user->name;
            $user->delete();

            // 🔹 Ghi log thành công
            AuditHelper::log('Xóa người dùng', $name, 'Thành công');

            return redirect()->route('users.index')->with('success', 'Xóa người dùng thành công.');
        } catch (\Exception $e) {
            AuditHelper::log('Xóa người dùng', $user->name ?? 'Không rõ', 'Thất bại');
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xóa người dùng.');
        }
    }

    public function show($id)
{
    $user = User::findOrFail($id);

    // Nếu user có quan hệ role
    $user->load('role'); // thay 'roles' bằng 'role' nếu mỗi user 1 role

    return view('users.show', compact('user'));
}

}
