<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo user cho sinh viên đầu tiên (SV2021001)
        $student = Student::where('student_id', 'SV2021001')->first();
        
        if ($student && !$student->user_id) {
            $user = User::firstOrCreate(
                ['email' => 'student@example.com'],
                [
                    'name' => 'Nguyễn Văn An',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            // Gán role Student
            if (!$user->hasRole('Student')) {
                $user->assignRole('Student');
            }

            // Liên kết user với student
            $student->update(['user_id' => $user->id]);
        }

        // Tạo thêm một vài user student khác để test
        $students = Student::whereNull('user_id')->take(2)->get();
        
        foreach ($students as $index => $student) {
            $user = User::create([
                'name' => $student->full_name,
                'email' => 'student' . ($index + 2) . '@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('Student');
            $student->update(['user_id' => $user->id]);
        }
    }
}

