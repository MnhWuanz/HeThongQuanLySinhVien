<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $primaryKey = 'department_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'department_id',
        'name',
    ];

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'department', 'department_id');
    }
}
