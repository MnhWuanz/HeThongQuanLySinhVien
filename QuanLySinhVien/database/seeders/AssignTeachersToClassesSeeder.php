<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class AssignTeachersToClassesSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = Teacher::all();

        if ($teachers->isEmpty()) {
            $this->command->warn('No teachers found. Please run TeacherSeeder first.');
            return;
        }

        $classes = ClassModel::all();

        foreach ($classes as $index => $class) {
            // Gán giảng viên cho lớp (round-robin)
            $teacher = $teachers[$index % $teachers->count()];

            $class->update([
                'teacher_id' => $teacher->id,
            ]);

            $this->command->info("Assigned {$teacher->full_name} to class {$class->class_id}");
        }

        $this->command->info('Teachers assigned to classes successfully!');
    }
}
