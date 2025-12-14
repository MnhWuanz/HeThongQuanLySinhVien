<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            // Khoa CNTT
            ['name' => 'TS. Nguyễn Văn Anh', 'email' => 'nva@university.edu.vn', 'phone' => '0901234567', 'department_id' => 'CNTT', 'specialization' => 'Khoa học máy tính'],
            ['name' => 'ThS. Trần Thị Bích', 'email' => 'ttb@university.edu.vn', 'phone' => '0901234568', 'department_id' => 'CNTT', 'specialization' => 'Công nghệ phần mềm'],
            ['name' => 'PGS.TS. Lê Văn Cường', 'email' => 'lvc@university.edu.vn', 'phone' => '0901234569', 'department_id' => 'CNTT', 'specialization' => 'Trí tuệ nhân tạo'],
            ['name' => 'ThS. Phạm Thị Dung', 'email' => 'ptd@university.edu.vn', 'phone' => '0901234570', 'department_id' => 'CNTT', 'specialization' => 'Mạng máy tính'],
            ['name' => 'TS. Hoàng Văn Em', 'email' => 'hve@university.edu.vn', 'phone' => '0901234571', 'department_id' => 'CNTT', 'specialization' => 'An toàn thông tin'],

            // Thêm giảng viên khác
            ['name' => 'ThS. Vũ Thị Hoa', 'email' => 'vth@university.edu.vn', 'phone' => '0901234572', 'department_id' => 'CNTT', 'specialization' => 'Cơ sở dữ liệu'],
            ['name' => 'TS. Đỗ Văn Kiên', 'email' => 'dvk@university.edu.vn', 'phone' => '0901234573', 'department_id' => 'CNTT', 'specialization' => 'Lập trình'],
            ['name' => 'ThS. Bùi Thị Linh', 'email' => 'btl@university.edu.vn', 'phone' => '0901234574', 'department_id' => 'CNTT', 'specialization' => 'Web Development'],
        ];

        foreach ($teachers as $index => $teacherData) {
            // Tạo user cho giảng viên
            $user = User::create([
                'name' => $teacherData['name'],
                'email' => $teacherData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'Teacher',
            ]);

            // Tạo teacher
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'full_name' => $teacherData['name'],
                'phone' => $teacherData['phone'],
                'address' => null,
            ]);

            $this->command->info("Created teacher: {$teacherData['name']}");
        }

        $this->command->info('Teachers seeded successfully!');
    }
}
