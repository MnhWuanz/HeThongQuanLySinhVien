<?php

namespace App\Filament\Student\Pages;

use Filament\Pages\Page;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.student.pages.dashboard';

    protected static ?string $navigationLabel = 'Trang chủ';

    protected static ?int $navigationSort = 1;

    public function getStudent()
    {
        return Student::where('user_id', Auth::id())
            ->with(['classRelation.departmentRelation', 'scores.subject'])
            ->firstOrFail();
    }

    public function getStatisticsProperty()
    {
        $student = $this->getStudent();
        $scores = $student->scores;

        return [
            'total_subjects' => $scores->count(),
            'passed_subjects' => $scores->where('total', '>=', 5)->count(),
            'failed_subjects' => $scores->where('total', '<', 5)->count(),
            'average_score' => $scores->avg('total') ?? 0,
            'highest_score' => $scores->max('total') ?? 0,
            'lowest_score' => $scores->min('total') ?? 0,
        ];
    }

    public function getHeading(): string
    {
        return 'Chào mừng, ' . $this->getStudent()->full_name;
    }

    protected function getViewData(): array
    {
        $student = $this->getStudent();

        return [
            'student' => $student,
            'statistics' => $this->statistics,
        ];
    }
}
