<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

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

    protected static function boot()
    {
        parent::boot();

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
}

