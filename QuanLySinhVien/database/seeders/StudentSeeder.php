<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'student_id' => 'SV2021001',
                'full_name' => 'Nguyễn Văn An',
                'birth_date' => '2003-05-15',
                'avatar' => null,
                'class_id' => 'CNTT2021A',
            ],
            [
                'student_id' => 'SV2021002',
                'full_name' => 'Trần Thị Bình',
                'birth_date' => '2003-08-20',
                'avatar' => null,
                'class_id' => 'CNTT2021A',
            ],
            [
                'student_id' => 'SV2021003',
                'full_name' => 'Lê Văn Cường',
                'birth_date' => '2003-02-10',
                'avatar' => null,
                'class_id' => 'CNTT2021B',
            ],
            [
                'student_id' => 'SV2021004',
                'full_name' => 'Phạm Thị Dung',
                'birth_date' => '2003-11-25',
                'avatar' => null,
                'class_id' => 'CNTT2021B',
            ],
            [
                'student_id' => 'SV2021005',
                'full_name' => 'Hoàng Văn Em',
                'birth_date' => '2003-07-08',
                'avatar' => null,
                'class_id' => 'CNTT2022A',
            ],
            [
                'student_id' => 'SV2021006',
                'full_name' => 'Vũ Thị Phương',
                'birth_date' => '2004-03-12',
                'avatar' => null,
                'class_id' => 'CNTT2022A',
            ],
            [
                'student_id' => 'SV2021007',
                'full_name' => 'Đỗ Văn Giang',
                'birth_date' => '2003-09-30',
                'avatar' => null,
                'class_id' => 'KT2021A',
            ],
            [
                'student_id' => 'SV2021008',
                'full_name' => 'Bùi Thị Hoa',
                'birth_date' => '2003-12-05',
                'avatar' => null,
                'class_id' => 'KT2021A',
            ],
            [
                'student_id' => 'SV2021009',
                'full_name' => 'Ngô Văn Hùng',
                'birth_date' => '2004-01-18',
                'avatar' => null,
                'class_id' => 'KT2022A',
            ],
            [
                'student_id' => 'SV2021010',
                'full_name' => 'Lý Thị Lan',
                'birth_date' => '2004-04-22',
                'avatar' => null,
                'class_id' => 'KT2022A',
            ],
            [
                'student_id' => 'SV2021011',
                'full_name' => 'Võ Văn Minh',
                'birth_date' => '2003-06-14',
                'avatar' => null,
                'class_id' => 'QTKD2021A',
            ],
            [
                'student_id' => 'SV2021012',
                'full_name' => 'Đinh Thị Nga',
                'birth_date' => '2003-10-28',
                'avatar' => null,
                'class_id' => 'QTKD2021A',
            ],
            [
                'student_id' => 'SV2021013',
                'full_name' => 'Trương Văn Oanh',
                'birth_date' => '2003-05-03',
                'avatar' => null,
                'class_id' => 'NN2021A',
            ],
            [
                'student_id' => 'SV2021014',
                'full_name' => 'Phan Thị Quỳnh',
                'birth_date' => '2003-08-17',
                'avatar' => null,
                'class_id' => 'NN2021A',
            ],
            [
                'student_id' => 'SV2021015',
                'full_name' => 'Lương Văn Sơn',
                'birth_date' => '2003-11-09',
                'avatar' => null,
                'class_id' => 'DL2021A',
            ],
            [
                'student_id' => 'SV2021016',
                'full_name' => 'Chu Thị Tuyết',
                'birth_date' => '2004-02-19',
                'avatar' => null,
                'class_id' => 'DL2021A',
            ],
            [
                'student_id' => 'SV2021017',
                'full_name' => 'Dương Văn Tuấn',
                'birth_date' => '2003-07-25',
                'avatar' => null,
                'class_id' => 'CNTT2021A',
            ],
            [
                'student_id' => 'SV2021018',
                'full_name' => 'Hồ Thị Uyên',
                'birth_date' => '2003-09-11',
                'avatar' => null,
                'class_id' => 'CNTT2021B',
            ],
            [
                'student_id' => 'SV2021019',
                'full_name' => 'Lâm Văn Việt',
                'birth_date' => '2004-03-07',
                'avatar' => null,
                'class_id' => 'CNTT2022A',
            ],
            [
                'student_id' => 'SV2021020',
                'full_name' => 'Mai Thị Xuân',
                'birth_date' => '2004-01-30',
                'avatar' => null,
                'class_id' => 'KT2021A',
            ],
        ];

        foreach ($students as $student) {
            Student::firstOrCreate(
                ['student_id' => $student['student_id']],
                $student
            );
        }
    }
}

