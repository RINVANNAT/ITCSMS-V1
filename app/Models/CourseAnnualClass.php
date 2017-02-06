<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAnnualClass extends Model
{
    public $table = "course_annual_classes";


    public $fillable = [
        "department_id",
        "degree_id",
        "grade_id",
        "course_annual_id",
        "create_uid",
        "write_uid"
    ];

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }

    public function department(){
        return $this->belongsTo('App\Models\Department');
    }

    public function departmentOption(){
        return $this->belongsTo('App\Models\DepartmentOption');
    }
    public function degree(){
        return $this->belongsTo('App\Models\Degree');
    }

    public function grade(){
        return $this->belongsTo('App\Models\Grade');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public static $rules = [
        "department_id" => "Required",
        "degree_id" => "Required|numeric",
        "grade_id" => "Required|numeric",
        "course_annual_id" => "Required|numeric"
    ];
}
