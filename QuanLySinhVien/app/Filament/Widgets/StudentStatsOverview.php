<?php

namespace App\Filament\Widgets;

use App\Models\Score;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StudentStatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected static ?string $pollingInterval = null;

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
            Stat::make('Số môn', $totalSubjects)
                ->description('Đã có điểm')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('info')
                ->extraAttributes(['class' => 'text-center']),
            Stat::make('GPA', number_format($averageScore, 2))
                ->description('Điểm TB')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color($averageScore >= 8.0 ? 'success' : ($averageScore >= 6.5 ? 'warning' : 'danger'))
                ->extraAttributes(['class' => 'text-center']),
            Stat::make('Đạt', $passedSubjects . '/' . $totalSubjects)
                ->description('≥ 5.0')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->extraAttributes(['class' => 'text-center']),
        ];
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->hasRole('Student');
    }
}
