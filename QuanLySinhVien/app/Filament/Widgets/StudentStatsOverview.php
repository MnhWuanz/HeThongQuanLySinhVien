<?php

namespace App\Filament\Widgets;

use App\Models\Score;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StudentStatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        
        if (!$user || !$user->hasRole('Student') || !$user->student) {
            return [];
        }

        $scores = Score::where('student_id', $user->student->student_id)->get();
        
        $totalSubjects = $scores->count();
        $averageScore = $scores->avg('total') ?? 0;
        $passedSubjects = $scores->where('total', '>=', 5.0)->count();

        return [
            Stat::make('Tổng số môn', $totalSubjects)
                ->description('Môn học đã có điểm')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('info'),
            Stat::make('Điểm trung bình', number_format($averageScore, 2))
                ->description('GPA của bạn')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color($averageScore >= 8.0 ? 'success' : ($averageScore >= 6.5 ? 'warning' : 'danger')),
            Stat::make('Môn đạt', $passedSubjects . '/' . $totalSubjects)
                ->description('Điểm >= 5.0')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->hasRole('Student');
    }
}
