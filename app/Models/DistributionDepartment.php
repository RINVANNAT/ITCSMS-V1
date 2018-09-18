<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionDepartment extends Model
{
    protected $fillable = [
        'student_annual_id',
        'department_id',
        'department_option_id',
        'priority',
        'score',
        'score_1',
        'score_2'
    ];

    protected $casts = [
        'score' => 'float'
    ];
}
