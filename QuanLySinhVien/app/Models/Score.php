<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Score extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'scores';

    protected $fillable = [
        'student_id',
        'subject_id',
        'cc',
        'gk',
        'ck',
        'total',
    ];

    protected $casts = [
        'cc' => 'decimal:2',
        'gk' => 'decimal:2',
        'ck' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function booted()
    {
        // Global scope for teacher
        static::addGlobalScope('teacher', function (Builder $builder) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            if (Auth::check() && $user && $user->hasRole('Teacher')) {
                $teacher = Teacher::where('user_id', Auth::id())->first();
                if ($teacher) {
                    // Chỉ hiển thị điểm của sinh viên trong lớp do giáo viên này chủ nhiệm
                    $builder->whereHas('student', function ($query) use ($teacher) {
                        $query->whereHas('classRelation', function ($classQuery) use ($teacher) {
                            $classQuery->where('teacher_id', $teacher->id);
                        });
                    });
                }
            }
        });

        static::saving(function ($score) {
            // Tự động tính tổng kết nếu chưa có hoặc các điểm thành phần thay đổi
            if ($score->cc !== null || $score->gk !== null || $score->ck !== null) {
                $cc = $score->cc ?? 0;
                $gk = $score->gk ?? 0;
                $ck = $score->ck ?? 0;
                $score->total = round($cc * 0.1 + $gk * 0.3 + $ck * 0.6, 2);
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['student_id', 'subject_id', 'cc', 'gk', 'ck', 'total'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => 'Tạo điểm mới',
                'updated' => 'Cập nhật điểm',
                'deleted' => 'Xóa điểm',
                default => $eventName,
            })
            ->useLogName('score_changes');
    }
}

