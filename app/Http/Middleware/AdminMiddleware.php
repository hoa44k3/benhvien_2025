<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra user đã login và có role admin chưa
       if (Auth::check()) {
        $user = Auth::user();
        $isAdmin = DB::table('user_roles')
                ->join('roles', 'user_roles.role_id', '=', 'roles.id')
                ->where('user_roles.user_id', $user->id)
                ->where('roles.name', 'admin')
                ->exists();

    if ($isAdmin) {
        return $next($request);
    }
}


        // Nếu không phải admin thì quay về trang chủ
        return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập vào trang admin.');
    }
}
