<?php

namespace App\Console\Commands;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MigrateAdvisorsToTeachers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:advisors-to-teachers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate advisors from classes to teachers table with user accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of advisors to teachers...');

        // Lấy danh sách advisor duy nhất từ bảng classes
        $advisors = ClassModel::select('advisor')
            ->distinct()
            ->whereNotNull('advisor')
            ->where('advisor', '!=', '')
            ->pluck('advisor')
            ->unique();

        $this->info("Found {$advisors->count()} unique advisors.");

        $bar = $this->output->createProgressBar($advisors->count());
        $bar->start();

        $created = 0;
        $skipped = 0;

        foreach ($advisors as $advisorName) {
            try {
                // Tạo email từ tên advisor (loại bỏ dấu, khoảng trắng)
                $slug = Str::slug($advisorName, '');
                $email = strtolower($slug) . '@teacher.edu.vn';

                // Kiểm tra xem user đã tồn tại chưa
                $user = User::where('email', $email)->first();

                if (!$user) {
                    // Tạo user mới cho giáo viên
                    $user = User::create([
                        'name' => $advisorName,
                        'email' => $email,
                        'password' => Hash::make('teacher123'), // Mật khẩu mặc định
                    ]);

                    // Gán role Teacher
                    $user->assignRole('Teacher');
                }

                // Kiểm tra xem teacher đã tồn tại chưa
                $teacherExists = DB::table('teachers')
                    ->where('user_id', $user->id)
                    ->exists();

                if (!$teacherExists) {
                    // Lưu vào bảng teachers
                    DB::table('teachers')->insert([
                        'user_id' => $user->id,
                        'full_name' => $advisorName,
                        'phone' => null,
                        'address' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $created++;
                } else {
                    $skipped++;
                }

            } catch (\Exception $e) {
                $this->error("Error migrating advisor '{$advisorName}': " . $e->getMessage());
                $skipped++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Migration completed!");
        $this->info("Created: {$created} teachers");
        $this->info("Skipped: {$skipped} advisors");

        return Command::SUCCESS;
    }
}
