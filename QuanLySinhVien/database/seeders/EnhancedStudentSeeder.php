<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EnhancedStudentSeeder extends Seeder
{
    private $firstNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương', 'Lý'];
    private $middleNames = ['Văn', 'Thị', 'Hữu', 'Minh', 'Quốc', 'Anh', 'Công', 'Đức', 'Hồng', 'Thanh'];
    private $lastNames = ['An', 'Bình', 'Cường', 'Dũng', 'Em', 'Giang', 'Hà', 'Hoa', 'Kiên', 'Linh', 'Mai', 'Nam', 'Phong', 'Quân', 'Sơn', 'Tài', 'Tâm', 'Tuấn', 'Vân', 'Vinh'];

    public function run(): void
    {
        $classes = ClassModel::all();

        if ($classes->isEmpty()) {
            $this->command->warn('No classes found. Please run ClassModelSeeder first.');
            return;
        }

        $studentCount = 0;
        $baseYear = 2021;

        foreach ($classes as $class) {
            // Lấy năm từ class_id (vd: CNTT2021A -> 2021)
            preg_match('/(\d{4})/', $class->class_id, $matches);
            $year = $matches[1] ?? $baseYear;

            // Mỗi lớp có 20-30 sinh viên
            $studentsPerClass = rand(20, 30);

            for ($i = 1; $i <= $studentsPerClass; $i++) {
                $studentCount++;
                $studentId = sprintf('SV%d%03d', $year, $studentCount);

                // Tạo tên ngẫu nhiên
                $fullName = $this->generateRandomName();

                // Tạo ngày sinh (18-25 tuổi)
                $birthYear = (int) $year - 18 - rand(0, 7);
                $birthDate = $birthYear . '-' . sprintf('%02d', rand(1, 12)) . '-' . sprintf('%02d', rand(1, 28));

                // Tạo email
                $email = strtolower($studentId) . '@student.edu.vn';

                // Tạo user account cho sinh viên
                $user = User::create([
                    'name' => $fullName,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);

                // Assign student role
                $user->assignRole('student');

                // Tạo student record
                Student::create([
                    'student_id' => $studentId,
                    'full_name' => $fullName,
                    'birth_date' => $birthDate,
                    'avatar' => null,
                    'class_id' => $class->class_id,
                    'user_id' => $user->id,
                ]);
            }

            $this->command->info("Created {$studentsPerClass} students for class {$class->class_id}");
        }

        $this->command->info("Total students created: {$studentCount}");
    }

    private function generateRandomName(): string
    {
        $firstName = $this->firstNames[array_rand($this->firstNames)];
        $middleName = $this->middleNames[array_rand($this->middleNames)];
        $lastName = $this->lastNames[array_rand($this->lastNames)];

        return "{$firstName} {$middleName} {$lastName}";
    }

    private function generateEmail(string $fullName, string $studentId): string
    {
        // Chuyển tên thành slug
        $parts = explode(' ', $fullName);
        $lastName = end($parts);
        $firstParts = array_slice($parts, 0, -1);

        $slug = strtolower($lastName);
        foreach ($firstParts as $part) {
            $slug .= strtolower(substr($part, 0, 1));
        }

        // Loại bỏ dấu tiếng Việt
        $slug = $this->removeVietnameseTones($slug);

        return $slug . '.' . strtolower($studentId) . '@student.edu.vn';
    }

    private function removeVietnameseTones(string $str): string
    {
        $vietnameseTones = [
            'à' => 'a',
            'á' => 'a',
            'ạ' => 'a',
            'ả' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'ầ' => 'a',
            'ấ' => 'a',
            'ậ' => 'a',
            'ẩ' => 'a',
            'ẫ' => 'a',
            'ă' => 'a',
            'ằ' => 'a',
            'ắ' => 'a',
            'ặ' => 'a',
            'ẳ' => 'a',
            'ẵ' => 'a',
            'è' => 'e',
            'é' => 'e',
            'ẹ' => 'e',
            'ẻ' => 'e',
            'ẽ' => 'e',
            'ê' => 'e',
            'ề' => 'e',
            'ế' => 'e',
            'ệ' => 'e',
            'ể' => 'e',
            'ễ' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'ị' => 'i',
            'ỉ' => 'i',
            'ĩ' => 'i',
            'ò' => 'o',
            'ó' => 'o',
            'ọ' => 'o',
            'ỏ' => 'o',
            'õ' => 'o',
            'ô' => 'o',
            'ồ' => 'o',
            'ố' => 'o',
            'ộ' => 'o',
            'ổ' => 'o',
            'ỗ' => 'o',
            'ơ' => 'o',
            'ờ' => 'o',
            'ớ' => 'o',
            'ợ' => 'o',
            'ở' => 'o',
            'ỡ' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'ụ' => 'u',
            'ủ' => 'u',
            'ũ' => 'u',
            'ư' => 'u',
            'ừ' => 'u',
            'ứ' => 'u',
            'ự' => 'u',
            'ử' => 'u',
            'ữ' => 'u',
            'ỳ' => 'y',
            'ý' => 'y',
            'ỵ' => 'y',
            'ỷ' => 'y',
            'ỹ' => 'y',
            'đ' => 'd',
        ];

        return strtr($str, $vietnameseTones);
    }
}
