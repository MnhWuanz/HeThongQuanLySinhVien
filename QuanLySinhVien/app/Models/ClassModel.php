<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClassModel extends Model
{
    protected $table = 'classes';

    protected $primaryKey = 'class_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'class_id',
        'department',
        'teacher_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope('teacher', function (Builder $builder) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            if (Auth::check() && $user && $user->hasRole('Teacher')) {
                $teacher = Teacher::where('user_id', Auth::id())->first();
                if ($teacher) {
                    $builder->where('teacher_id', $teacher->id);
                }
            }
        });
    }

    public function departmentRelation()
    {
        return $this->belongsTo(Department::class, 'department', 'department_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class', 'class_id');
    }
}
