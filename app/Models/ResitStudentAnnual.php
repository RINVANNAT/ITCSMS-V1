<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResitStudentAnnual extends Model
{
    public $fillable = [
        "date_time_resit",
        "resit_room",
        "resit_score",
        "course_annual_id",
        "student_annual_id",
        "create_uid",
        "write_uid"
    ];

    public $table = "resit_student_annuals";

    public function setResitDateAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/Y', $value);
        $this->attributes['date_time_resit'] = $date->format('Y/m/d');
    }

    public function studentAnnual(){
        return $this->belongsTo('App\Models\StudentAnnual');
    }
    public function courseAnnual() {
        return $this->belongsTo('App\Models\CourseAnnual');
    }
}
