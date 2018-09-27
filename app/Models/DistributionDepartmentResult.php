<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionDepartmentResult extends Model
{
    protected $fillable = [
        'academic_year_id',
        'distribution_department_id',
        'department_id',
        'department_option_id',
        'total_score',
        'priority',
        'student_annual_id',
        'grade_id'
    ];

    protected $appends = [
        'student'
    ];

    public function studentAnnual ()
    {
        return $this->belongsTo(StudentAnnual::class);
    }

    public function getStudentAttribute ()
    {
        return $this->studentAnnual->student;
    }

    public function department ()
    {
        return $this->belongsTo(Department::class);
    }

    public function departmentOption ()
    {
        return $this->belongsTo(DepartmentOption::class);
    }
}
