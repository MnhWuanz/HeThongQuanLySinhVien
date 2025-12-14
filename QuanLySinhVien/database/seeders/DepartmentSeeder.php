<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['department_id' => 'CNTT', 'name' => 'Công nghệ thông tin'],
            ['department_id' => 'KT', 'name' => 'Kế toán'],
            ['department_id' => 'QTKD', 'name' => 'Quản trị kinh doanh'],
            ['department_id' => 'NN', 'name' => 'Ngôn ngữ'],
            ['department_id' => 'DL', 'name' => 'Du lịch'],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['department_id' => $department['department_id']],
                $department
            );
        }
    }
}

