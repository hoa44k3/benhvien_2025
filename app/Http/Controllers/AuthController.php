<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Form đăng nhập
    public function loginForm()
    {
        return view('auth.login');
    }

//    public function login(Request $request)
// {
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required|string',
//     ]);

//     $email = $request->input('email');
//     $password = $request->input('password');

//     $user = User::where('email', $email)->first();

//     if (! $user) {
//         return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.']);
//     }

//     $stored = $user->password ?? '';

//     // Kiểm tra password có đúng chuẩn hash hợp lệ không
//     $looksHashed = preg_match('/^\$2[ayb]\$.{56}$/', $stored) 
//                  || str_starts_with($stored, '$argon2i$')
//                  || str_starts_with($stored, '$argon2id$');

//     if ($looksHashed) {
//         // Nếu password trong DB đúng chuẩn hash → dùng Auth::attempt
//         if (! Auth::attempt(['email' => $email, 'password' => $password])) {
//             return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.']);
//         }
//     } else {
//         // Nếu password trong DB là text thường
//         if (! hash_equals($stored, $password)) {
//             return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.']);
//         }

//         // Nếu đúng → cập nhật lại hash chuẩn
//         $user->password = Hash::make($password);
//         $user->save();

//         Auth::login($user);
//     }

//     $user = Auth::user();

//     // Lấy role
//     $role = DB::table('user_roles')
//         ->join('roles', 'user_roles.role_id', '=', 'roles.id')
//         ->where('user_roles.user_id', $user->id)
//         ->value('roles.name');

//     if (! $role) {
//         Auth::logout();
//         return back()->withErrors(['email' => 'Tài khoản chưa được gán vai trò.']);
//     }

//     // Điều hướng theo role
//     return match ($role) {
//         'admin'        => redirect()->route('admin.index'),
//         'doctor'       => redirect()->route('doctor.index'),
//         'nurse'        => redirect()->route('nurse.index'),
//         'pharmacist'   => redirect()->route('pharmacist.index'),
//         'receptionist' => redirect()->route('receptionist.index'),
//         default        => redirect()->route('home'),
//     };
// }
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $email = $request->email;
    $password = $request->password;

    $user = User::where('email', $email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
    }

    $storedPassword = $user->password;

    // Kiểm tra password đã hash hay chưa
    $isHashed = preg_match('/^\$2[ayb]\$.{56}$/', $storedPassword) 
              || str_starts_with($storedPassword, '$argon2i$')
              || str_starts_with($storedPassword, '$argon2id$');

    if ($isHashed) {
        // Nếu đã hash chuẩn → dùng Auth::attempt
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
        }
    } else {
        // Nếu password cũ chưa hash → so sánh trực tiếp
        if (!hash_equals($storedPassword, $password)) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
        }

        // Nếu đúng → hash lại password chuẩn
        $user->password = Hash::make($password);
        $user->save();

        Auth::login($user);
    }

    $user = Auth::user();

    // Lấy role
    $role = DB::table('user_roles')
        ->join('roles', 'user_roles.role_id', '=', 'roles.id')
        ->where('user_roles.user_id', $user->id)
        ->value('roles.name');

    if (!$role) {
        Auth::logout();
        return back()->withErrors(['email' => 'Tài khoản chưa được gán vai trò.']);
    }

    // Điều hướng theo role
    return match ($role) {
        'admin'        => redirect()->route('admin.index'),
        'doctor'       => redirect()->route('doctor.index'),
        'nurse'        => redirect()->route('nurse.index'),
        'pharmacist'   => redirect()->route('pharmacist.index'),
        'receptionist' => redirect()->route('receptionist.index'),
        default        => redirect()->route('home'),
    };
}





    // Form đăng ký
    public function registerForm()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        // Tạo user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? null,
            'password' => Hash::make($request->password),
        ]);

        // Gán vai trò cho user
        $role = Role::where('name', $request->role)->first();
        if ($role) {
            DB::table('user_roles')->insert([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        }
        // Đăng nhập ngay sau khi đăng ký
        Auth::login($user);

        // Điều hướng đến dashboard theo role
        switch ($role->name) {
            case 'admin':
                return redirect()->route('admin.index')->with('success', 'Đăng ký thành công! Chào mừng quản trị viên.');
            case 'doctor':
                return redirect()->route('doctor.index')->with('success', 'Đăng ký thành công! Chào mừng bác sĩ.');
            case 'nurse':
                return redirect()->route('nurse.index')->with('success', 'Đăng ký thành công! Chào mừng y tá.');
            case 'pharmacist':
                return redirect()->route('pharmacist.index')->with('success', 'Đăng ký thành công! Chào mừng dược sĩ.');
            case 'receptionist':
                return redirect()->route('receptionist.index')->with('success', 'Đăng ký thành công! Chào mừng lễ tân.');
            case 'patient':
                return redirect()->route('home')->with('success', 'Đăng ký thành công!');
            default:
                return redirect()->route('home')->with('success', 'Đăng ký thành công!');
        }
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đã đăng xuất!');
    }
}
