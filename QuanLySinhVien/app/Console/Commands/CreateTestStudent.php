<?php

namespace App\Console\Commands;

use App\Models\ClassModel;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Console\Command;

class CreateTestStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-student';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tạo tài khoản sinh viên test';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang tạo tài khoản test...');

        // Kiểm tra đã tồn tại chưa
        if (User::where('email', 'test@student.edu.vn')->exists()) {
            $this->error('Tài khoản test@student.edu.vn đã tồn tại!');
            return;
        }

        // Tạo user
        $user = User::create([
            'name' => 'Sinh Viên Test',
            'email' => 'test@student.edu.vn',
            'password' => bcrypt('123456'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('Student');
        $this->info('✅ Đã tạo User');

        // Lấy class đầu tiên
        $class = ClassModel::first();
        if (!$class) {
            $this->error('Không tìm thấy lớp nào trong database!');
            return;
        }

        // Tạo student
        $student = Student::create([
            'student_id' => 'TEST001',
            'full_name' => 'Sinh Viên Test',
            'birth_date' => '2000-01-01',
            'class_id' => $class->class_id,
            'user_id' => $user->id,
        ]);
        $this->info('✅ Đã tạo Student (Mã: TEST001)');

        // Tạo điểm test cho các môn
        $subjects = Subject::limit(5)->get();
        if ($subjects->isEmpty()) {
            $this->warn('⚠️ Không có môn học nào để tạo điểm test');
        } else {
            foreach ($subjects as $subject) {
                $cc = rand(70, 100) / 10;
                $gk = rand(50, 90) / 10;
                $ck = rand(50, 90) / 10;
                $total = round($cc * 0.1 + $gk * 0.3 + $ck * 0.6, 2);

                Score::create([
                    'student_id' => 'TEST001',
                    'subject_id' => $subject->subject_id,
                    'cc' => $cc,
                    'gk' => $gk,
                    'ck' => $ck,
                    'total' => $total,
                ]);
            }
            $this->info('✅ Đã tạo ' . $subjects->count() . ' điểm test');
        }

        $this->newLine();
        $this->info('🎉 Tạo thành công tài khoản test!');
        $this->info('📧 Email: test@student.edu.vn');
        $this->info('🔑 Password: 123456');
        $this->info('👤 Mã SV: TEST001');
    }
}
