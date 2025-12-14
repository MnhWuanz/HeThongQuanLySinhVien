<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Department;
use Illuminate\Database\Seeder;

class ClassModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy departments
        $departments = Department::all()->keyBy('department_id');

        if ($departments->isEmpty()) {
            $this->command->warn('No departments found. Please seed departments first.');
            return;
        }

        $classes = [
            ['class_id' => 'CNTT2021A', 'department' => 'CNTT'],
            ['class_id' => 'CNTT2021B', 'department' => 'CNTT'],
            ['class_id' => 'CNTT2022A', 'department' => 'CNTT'],
            ['class_id' => 'CNTT2022B', 'department' => 'CNTT'],
            ['class_id' => 'CNTT2023A', 'department' => 'CNTT'],
            ['class_id' => 'CNTT2023B', 'department' => 'CNTT'],
            ['class_id' => 'CNTT2024A', 'department' => 'CNTT'],
            ['class_id' => 'CNTT2024B', 'department' => 'CNTT'],
        ];

        foreach ($classes as $class) {
            // Kiểm tra xem department có tồn tại không
            if (!isset($departments[$class['department']])) {
                continue;
            }

            ClassModel::firstOrCreate(
                ['class_id' => $class['class_id']],
                [
                    'class_id' => $class['class_id'],
                    'department' => $class['department'],
                    'teacher_id' => null, // Có thể cập nhật sau khi có teacher
                ]
            );
        }

        $this->command->info('Created ' . count($classes) . ' classes successfully!');
    }
}

