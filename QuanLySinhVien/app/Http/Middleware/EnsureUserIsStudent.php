<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStudent
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
        
        // Bỏ qua middleware cho các route login
        if (str_contains($path, '/login') || 
            str_contains($path, '/register') ||
            $path === 'student') {
            return $next($request);
        }

        // Nếu chưa đăng nhập, Filament sẽ tự động redirect đến login
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        // Kiểm tra role sau khi đã đăng nhập
        if (!$user->hasRole('Student')) {
            // Đăng xuất user không có quyền và redirect về login
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('filament.student.auth.login')
                ->withErrors(['email' => 'Tài khoản này không có quyền truy cập panel sinh viên.']);
        }

        return $next($request);
    }
}

