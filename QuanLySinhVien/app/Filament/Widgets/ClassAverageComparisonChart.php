<?php

namespace App\Filament\Widgets;

use App\Models\ClassModel;
use App\Models\Score;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassAverageComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'So sánh điểm TB giữa các lớp';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

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

        // Nếu là Teacher, chỉ xem lớp mình chủ nhiệm
        if ($user->hasRole('Teacher') && $user->teacher) {
            $classes = ClassModel::where('teacher_id', $user->teacher->id)->get();
        }
        // Admin và Student xem tất cả lớp
        else {
            $classes = ClassModel::withCount('students')->having('students_count', '>', 0)->get();
        }

        if ($classes->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Chưa có dữ liệu',
                        'data' => [0],
                        'backgroundColor' => ['#e5e7eb'],
                    ],
                ],
                'labels' => ['Chưa có lớp'],
            ];
        }

        $labels = [];
        $averages = [];
        $colors = [];

        foreach ($classes as $class) {
            // Tính điểm TB của lớp
            $classAverage = Score::whereHas('student', function ($query) use ($class) {
                $query->where('class_id', $class->class_id);
            })->avg('total');

            if ($classAverage !== null) {
                $labels[] = $class->class_id;
                $averages[] = round($classAverage, 2);
                
                // Màu sắc theo điểm TB
                if ($classAverage >= 8.0) {
                    $colors[] = 'rgb(34, 197, 94)'; // green-500
                } elseif ($classAverage >= 7.0) {
                    $colors[] = 'rgb(59, 130, 246)'; // blue-500
                } elseif ($classAverage >= 6.0) {
                    $colors[] = 'rgb(245, 158, 11)'; // amber-500
                } elseif ($classAverage >= 5.0) {
                    $colors[] = 'rgb(249, 115, 22)'; // orange-500
                } else {
                    $colors[] = 'rgb(239, 68, 68)'; // red-500
                }
            }
        }

        if (empty($labels)) {
            return [
                'datasets' => [
                    [
                        'label' => 'Chưa có dữ liệu',
                        'data' => [0],
                        'backgroundColor' => ['#e5e7eb'],
                    ],
                ],
                'labels' => ['Chưa có điểm'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Điểm trung bình',
                    'data' => $averages,
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 10,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Điểm',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Lớp',
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        // Chỉ Admin và Teacher được xem
        return $user && ($user->hasRole('Super_Admin') || $user->hasRole('Teacher'));
    }
}
