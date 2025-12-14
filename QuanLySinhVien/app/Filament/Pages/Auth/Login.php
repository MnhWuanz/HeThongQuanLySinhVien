<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected function throwFailureValidationException(): never
    {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'data.email' => 'Thông tin đăng nhập sai',
        ]);
    }

    public function getHeading(): string
    {
        return 'Đăng nhập Quản trị';
    }

    public function getSubHeading(): string
    {
        return 'Hệ thống Quản lý Sinh viên - Trường Đại học ABC';
    }
}
