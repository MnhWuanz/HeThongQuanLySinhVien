<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    protected $table = 'classes';

    protected $primaryKey = 'class_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'class_id',
        'department',
        'advisor',
    ];

    public function departmentRelation()
    {
        return $this->belongsTo(Department::class, 'department', 'department_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class', 'class_id');
    }
}
