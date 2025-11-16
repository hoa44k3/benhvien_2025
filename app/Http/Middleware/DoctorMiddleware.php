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
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra user đã login và có role doctor chưa
        // if (Auth::check()) {
        //     $user = Auth::user();

        //     $isDoctor = DB::table('user_roles')
        //         ->join('roles', 'user_roles.role_id', '=', 'roles.id')
        //         ->where('user_roles.user_id', $user->id)
        //         ->where('roles.name', 'doctor')
        //         ->exists();

        //     if ($isDoctor) {
        //         return $next($request);
        //     }
        // }

        // // Nếu không phải doctor thì quay về home
        // return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang bác sĩ.');
        if (!Auth::check() || Auth::user()->role !== 'doctor') {
            abort(403, 'Chỉ bác sĩ mới có quyền truy cập.');
        }

        // Nếu là bác sĩ thì cho qua
        return $next($request);
    }
}
