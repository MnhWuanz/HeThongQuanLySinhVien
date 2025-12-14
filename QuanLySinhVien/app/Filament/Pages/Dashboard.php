<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getHeading(): string
    {
        return 'Tổng quan Hệ thống';
    }

    public function getSubheading(): string|null
    {
        $user = auth()->user();
        $greeting = $this->getGreeting();

        return "{$greeting}, {$user->name}! Chào mừng đến với Hệ thống Quản lý Sinh viên.";
    }

    protected function getGreeting(): string
    {
        $hour = now()->hour;

        if ($hour < 12) {
            return 'Chào buổi sáng';
        } elseif ($hour < 18) {
            return 'Chào buổi chiều';
        } else {
            return 'Chào buổi tối';
        }
    }

    protected function getHeaderWidgets(): array
    {
        $user = auth()->user();

        // Admin/Teacher widgets
        if ($user->hasRole('Super_Admin')) {
            return [
                \App\Filament\Widgets\StudentStatsOverview::class,
            ];
        }

        // Teacher widgets
        if ($user->hasRole('Teacher')) {
            return [
                \App\Filament\Widgets\TeacherStatsOverview::class,
            ];
        }

        return [];
    }

    protected function getFooterWidgets(): array
    {
        $user = auth()->user();

        if ($user->hasRole('Super_Admin')) {
            return [
                \App\Filament\Widgets\PassFailRatioChart::class,
                \App\Filament\Widgets\ClassAverageComparisonChart::class,
            ];
        }

        if ($user->hasRole('Teacher')) {
            return [
                \App\Filament\Widgets\TeacherClassesTable::class,
            ];
        }

        return [];
    }
}
