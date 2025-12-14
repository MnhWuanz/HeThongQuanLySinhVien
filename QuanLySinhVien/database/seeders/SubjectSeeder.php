<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // Môn học năm 1
            ['subject_id' => 'IT001', 'name' => 'Nhập môn Lập trình', 'credit' => 3],
            ['subject_id' => 'IT002', 'name' => 'Cấu trúc Dữ liệu và Giải thuật', 'credit' => 4],
            ['subject_id' => 'IT003', 'name' => 'Cơ sở Dữ liệu', 'credit' => 3],
            ['subject_id' => 'IT004', 'name' => 'Hệ điều hành', 'credit' => 3],
            ['subject_id' => 'IT005', 'name' => 'Mạng máy tính', 'credit' => 3],

            // Môn học năm 2
            ['subject_id' => 'IT101', 'name' => 'Lập trình Hướng đối tượng', 'credit' => 4],
            ['subject_id' => 'IT102', 'name' => 'Phát triển Ứng dụng Web', 'credit' => 4],
            ['subject_id' => 'IT103', 'name' => 'Công nghệ phần mềm', 'credit' => 3],
            ['subject_id' => 'IT104', 'name' => 'Trí tuệ nhân tạo', 'credit' => 3],
            ['subject_id' => 'IT105', 'name' => 'An toàn và Bảo mật', 'credit' => 3],

            // Môn học năm 3
            ['subject_id' => 'IT201', 'name' => 'Lập trình Di động', 'credit' => 4],
            ['subject_id' => 'IT202', 'name' => 'Phân tích và Thiết kế Hệ thống', 'credit' => 3],
            ['subject_id' => 'IT203', 'name' => 'Học máy và Khai phá dữ liệu', 'credit' => 4],
            ['subject_id' => 'IT204', 'name' => 'Điện toán đám mây', 'credit' => 3],
            ['subject_id' => 'IT205', 'name' => 'Blockchain và Ứng dụng', 'credit' => 3],

            // Môn toán và khoa học cơ bản
            ['subject_id' => 'MA001', 'name' => 'Toán cao cấp 1', 'credit' => 3],
            ['subject_id' => 'MA002', 'name' => 'Toán cao cấp 2', 'credit' => 3],
            ['subject_id' => 'MA003', 'name' => 'Toán rời rạc', 'credit' => 3],
            ['subject_id' => 'PH001', 'name' => 'Vật lý đại cương', 'credit' => 3],
            ['subject_id' => 'EN001', 'name' => 'Tiếng Anh chuyên ngành', 'credit' => 2],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        $this->command->info('Created ' . count($subjects) . ' subjects successfully!');
    }
}
