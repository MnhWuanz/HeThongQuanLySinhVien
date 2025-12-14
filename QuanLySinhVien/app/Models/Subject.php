<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $primaryKey = 'subject_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'subject_id',
        'name',
        'credit',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    protected function casts(): array
    {
        return [
            'credit' => 'integer',
        ];
    }
}


