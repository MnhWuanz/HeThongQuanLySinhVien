<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $primaryKey = 'student_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'full_name',
        'birth_date',
        'avatar',
        'class_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function classRelation()
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'class_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'student_id', 'student_id');
    }
}

