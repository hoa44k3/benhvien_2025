<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class DoctorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     // // Nếu không phải doctor thì quay về home
    //     // return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang bác sĩ.');
    //     if (!Auth::check() || Auth::user()->role !== 'doctor') {
    //         abort(403, 'Chỉ bác sĩ mới có quyền truy cập.');
    //     }

    //     // Nếu là bác sĩ thì cho qua
    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Cách 1: Check qua bảng trung gian (giống AuthController bạn đang làm)
            $isDoctor = DB::table('user_roles')
                ->join('roles', 'user_roles.role_id', '=', 'roles.id')
                ->where('user_roles.user_id', $user->id)
                ->where('roles.name', 'doctor')
                ->exists();

            // Hoặc Cách 2: Nếu Model User đã cấu hình relationship roles()
            // $isDoctor = $user->roles()->where('name', 'doctor')->exists();

            if ($isDoctor) {
                return $next($request);
            }
        }

        // Nếu sai quyền thì đá về trang home hoặc trang lỗi
        return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang bác sĩ.');
    }
}
