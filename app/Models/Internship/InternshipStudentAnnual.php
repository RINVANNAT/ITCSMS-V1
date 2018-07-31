<?php

namespace App\Models\Internship;

use App\Models\Department;
use App\Models\Gender;
use App\Models\Student;
use App\Models\StudentAnnual;
use Illuminate\Database\Eloquent\Model;

class InternshipStudentAnnual extends Model
{
    protected $fillable = [
        'internship_id',
        'student_annual_id'
    ];

    protected $appends = [
        'student'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function internship(){
        return $this->belongsTo(Internship::class);
    }

    public function getStudentAttribute()
    {
        $studentAnnual = StudentAnnual::find($this->id);
        $department = Department::find($studentAnnual->department_id);
        $student = Student::find($studentAnnual->student_id);
        $gender = Gender::find($student->gender_id);

        $student['department'] = $department->code;
        $student['gender'] = $gender->code;

        return $student;
    }
}
