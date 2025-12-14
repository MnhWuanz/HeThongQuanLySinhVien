<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');

        // Seed dữ liệu mẫu theo thứ tự
        $this->call([
                // 1. Roles và Admin
            RoleSeeder::class,
            AdminSeeder::class,

                // 2. Departments và Classes
            DepartmentSeeder::class,
            ClassModelSeeder::class,

                // 3. Subjects và Teachers
            SubjectSeeder::class,
            TeacherSeeder::class,
            AssignTeachersToClassesSeeder::class,

                // 4. Students (nhiều sinh viên)
            EnhancedStudentSeeder::class,

                // 5. Scores
            ScoreSeeder::class,
        ]);

        $this->command->info('All seeders completed successfully!');
        $this->command->info('Default credentials:');
        $this->command->info('Admin: admin@sms.edu.vn / password');
        $this->command->info('Teachers: [email]@university.edu.vn / password');
        $this->command->info('Students: [generated-email] / password');
    }
}
