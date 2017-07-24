<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetencyScore extends Model
{
    public $table = "competency_scores";
    public $fillable = [
        "score",
        "student_annual_id",
        "course_annual_id",
        "competency_id",
        "create_uid",
        "write_uid"
    ];

}
