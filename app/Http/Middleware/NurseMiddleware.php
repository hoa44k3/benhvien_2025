<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class NurseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       // Kiểm tra user đã login và có role nurse chưa
        if (Auth::check()) {
            $user = Auth::user();

            $isNurse = DB::table('user_roles')
                ->join('roles', 'user_roles.role_id', '=', 'roles.id')
                ->where('user_roles.user_id', $user->id)
                ->where('roles.name', 'nurse')
                ->exists();

            if ($isNurse) {
                return $next($request);
            }
        }

        // Nếu không phải doctor thì quay về home
        return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang y tá.');
    }
}
