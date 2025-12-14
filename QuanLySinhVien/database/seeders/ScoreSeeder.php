<?php

namespace Database\Seeders;

use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ScoreSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $subjects = Subject::all();

        if ($students->isEmpty() || $subjects->isEmpty()) {
            $this->command->warn('No students or subjects found. Please seed students and subjects first.');
            return;
        }

        $count = 0;

        foreach ($students as $student) {
            // Mỗi sinh viên học ngẫu nhiên 5-10 môn
            $numberOfSubjects = rand(5, 10);
            $randomSubjects = $subjects->random(min($numberOfSubjects, $subjects->count()));

            foreach ($randomSubjects as $subject) {
                // Tạo điểm ngẫu nhiên
                $cc = $this->randomScore(); // Điểm chuyên cần
                $gk = $this->randomScore(); // Điểm giữa kỳ
                $ck = $this->randomScore(); // Điểm cuối kỳ

                // Tính điểm tổng kết: CC(10%) + GK(30%) + CK(60%)
                $total = round(($cc * 0.1) + ($gk * 0.3) + ($ck * 0.6), 2);

                Score::create([
                    'student_id' => $student->student_id,
                    'subject_id' => $subject->subject_id,
                    'cc' => $cc,
                    'gk' => $gk,
                    'ck' => $ck,
                    'total' => $total,
                ]);

                $count++;
            }
        }

        $this->command->info("Created {$count} score records successfully!");
    }

    /**
     * Generate random score between 0 and 10
     */
    private function randomScore(): float
    {
        // Tạo điểm theo phân phối: 70% điểm khá-giỏi (6-10), 30% điểm thấp hơn
        if (rand(1, 10) <= 7) {
            // Điểm khá-giỏi (6.0 - 10.0)
            return round(rand(60, 100) / 10, 1);
        } else {
            // Điểm trung bình-yếu (0.0 - 5.9)
            return round(rand(0, 59) / 10, 1);
        }
    }
}
