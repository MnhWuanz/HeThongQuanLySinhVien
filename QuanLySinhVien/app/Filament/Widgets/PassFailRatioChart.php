<?php

namespace App\Filament\Widgets;

use App\Models\Score;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PassFailRatioChart extends ChartWidget
{
    protected static ?string $heading = 'Tỷ lệ Đạt/Trượt';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        
        if (!$user) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Nếu là Student, chỉ xem điểm của mình
        if ($user->hasRole('Student') && $user->student) {
            $scores = Score::where('student_id', $user->student->student_id)->get();
        }
        // Nếu là Teacher, xem điểm của lớp mình chủ nhiệm
        elseif ($user->hasRole('Teacher') && $user->teacher) {
            $scores = Score::whereHas('student', function ($query) use ($user) {
                $query->whereHas('classRelation', function ($classQuery) use ($user) {
                    $classQuery->where('teacher_id', $user->teacher->id);
                });
            })->get();
        }
        // Admin xem tất cả
        else {
            $scores = Score::all();
        }

        $passed = $scores->where('total', '>=', 5.0)->count();
        $failed = $scores->where('total', '<', 5.0)->count();
        $total = $scores->count();

        if ($total === 0) {
            return [
                'datasets' => [
                    [
                        'label' => 'Chưa có dữ liệu',
                        'data' => [1],
                        'backgroundColor' => ['#e5e7eb'],
                    ],
                ],
                'labels' => ['Chưa có điểm'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Số môn',
                    'data' => [$passed, $failed],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)', // green-500 (Đạt)
                        'rgb(239, 68, 68)', // red-500 (Trượt)
                    ],
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => [
                'Đạt (≥5.0): ' . $passed . ' môn',
                'Trượt (<5.0): ' . $failed . ' môn',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }

    public static function canView(): bool
    {
        return Auth::check();
    }
}
