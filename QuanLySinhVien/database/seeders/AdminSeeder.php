<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tài khoản Super Admin nếu chưa tồn tại
        User::firstOrCreate(
            ['email' => 'admin@sms.edu.vn'],
            [
                'name' => 'Super Admin',
                'password' => 'password', // Sẽ được tự động hash bởi User model cast
                'email_verified_at' => now(),
                'role' => 'Super_Admin',
            ]
        );
    }
}

