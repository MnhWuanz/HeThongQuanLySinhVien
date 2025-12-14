<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class StudentLogin extends BaseLogin
{
    protected function throwFailureValidationException(): never
    {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'data.email' => 'Thông tin đăng nhập sai hoặc tài khoản không có quyền truy cập.',
        ]);
    }

    protected function hasFullWidthForm(): bool
    {
        return false;
    }

    public function getHeading(): string
    {
        return '🎓 Chào mừng Sinh viên';
    }

    public function getSubHeading(): string
    {
        return 'Cổng thông tin Sinh viên - Trường Đại học ABC';
    }
}

