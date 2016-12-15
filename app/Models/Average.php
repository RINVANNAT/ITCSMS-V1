<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Average extends Model
{

    public $table = "averages";
    public $fillable = [
        "average",
        "student_annual_id",
        "course_annual_id",
        "total_average_id",
        "create_uid"
    ];


    public function scores() {
        return $this->hasMany('Score','id');
    }
}
