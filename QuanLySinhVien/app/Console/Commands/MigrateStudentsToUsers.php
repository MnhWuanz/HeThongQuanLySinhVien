<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MigrateStudentsToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:students-to-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing students to users and update student user_id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of students to users...');

        $students = Student::whereNull('user_id')->get();
        $this->info("Found {$students->count()} students without user accounts.");

        $bar = $this->output->createProgressBar($students->count());
        $bar->start();

        $created = 0;
        $skipped = 0;

        foreach ($students as $student) {
            try {
                // Tạo email từ student_id
                $email = strtolower($student->student_id) . '@student.edu.vn';

                // Kiểm tra xem user đã tồn tại chưa
                $user = User::where('email', $email)->first();

                if (!$user) {
                    // Tạo user mới
                    $user = User::create([
                        'name' => $student->full_name,
                        'email' => $email,
                        'password' => Hash::make($student->student_id), // Mật khẩu mặc định là mã sinh viên
                    ]);

                    // Gán role Student
                    $user->assignRole('Student');
                    $created++;
                }

                // Update user_id cho student
                $student->user_id = $user->id;
                $student->save();

            } catch (\Exception $e) {
                $this->error("Error migrating student {$student->student_id}: " . $e->getMessage());
                $skipped++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migration completed!");
        $this->info("Created: {$created} users");
        $this->info("Skipped: {$skipped} students");

        return Command::SUCCESS;
    }
}
