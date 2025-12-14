<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use Illuminate\Database\Seeder;

class ClassModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['class_id' => 'CNTT2021A', 'department' => 'CNTT', 'advisor' => 'Nguyễn Văn A'],
            ['class_id' => 'CNTT2021B', 'department' => 'CNTT', 'advisor' => 'Trần Thị B'],
            ['class_id' => 'CNTT2022A', 'department' => 'CNTT', 'advisor' => 'Lê Văn C'],
            ['class_id' => 'KT2021A', 'department' => 'KT', 'advisor' => 'Phạm Thị D'],
            ['class_id' => 'KT2022A', 'department' => 'KT', 'advisor' => 'Hoàng Văn E'],
            ['class_id' => 'QTKD2021A', 'department' => 'QTKD', 'advisor' => 'Vũ Thị F'],
            ['class_id' => 'NN2021A', 'department' => 'NN', 'advisor' => 'Đỗ Văn G'],
            ['class_id' => 'DL2021A', 'department' => 'DL', 'advisor' => 'Bùi Thị H'],
        ];

        foreach ($classes as $class) {
            ClassModel::firstOrCreate(
                ['class_id' => $class['class_id']],
                $class
            );
        }
    }
}

