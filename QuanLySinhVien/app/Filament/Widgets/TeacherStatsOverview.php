<?php

namespace App\Filament\Widgets;

use App\Models\ClassModel;
use App\Models\Score;
use App\Models\Student;
use App\Models\Teacher;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TeacherStatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        
        if (!$user || !$user->hasRole('Teacher')) {
            return [];
        }

        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            return [];
        }

        $classCount = ClassModel::where('teacher_id', $teacher->id)->count();
        
        $studentCount = Student::whereHas('classRelation', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->count();

        $scoreCount = Score::whereHas('student', function ($query) use ($teacher) {
            $query->whereHas('classRelation', function ($classQuery) use ($teacher) {
                $classQuery->where('teacher_id', $teacher->id);
            });
        })->count();

        return [
            Stat::make('Số lớp phụ trách', $classCount)
                ->description('Lớp bạn đang chủ nhiệm')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('success'),
            Stat::make('Tổng số sinh viên', $studentCount)
                ->description('Sinh viên trong các lớp')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),
            Stat::make('Điểm đã nhập', $scoreCount)
                ->description('Tổng số điểm đã ghi')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning'),
        ];
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->hasRole('Teacher');
    }
}
