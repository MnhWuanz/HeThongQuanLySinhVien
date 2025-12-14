<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdminOrTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cho phép truy cập trang login và các route authentication
        $path = $request->path();

        // Bỏ qua middleware cho các route login/register
        if (
            str_contains($path, '/login') ||
            str_contains($path, '/register')
        ) {
            return $next($request);
        }

        // Nếu chưa đăng nhập, Filament sẽ tự động redirect đến login
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        // Kiểm tra role - chỉ cho phép Super_Admin hoặc Teacher
        if (!$user->hasRole(['Super_Admin', 'Teacher'])) {
            // Đăng xuất user không có quyền và redirect về login
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('filament.admin.auth.login')
                ->withErrors(['email' => 'Tài khoản này không có quyền truy cập panel quản trị.']);
        }

        return $next($request);
    }
}
