<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSession extends Model
{
    public $table = "course_sessions";


    public $fillable = [
        "time_tp",
        "time_course",
        "time_td",
        "course_annual_id",
        "lecturer_id",
        "create_uid",
        "write_uid",
        "created_at",
        "updated_at"
    ];

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }

    public function updater(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }

    public function lecturer(){
        return $this->belongsTo('App\Models\Employee');
    }

    public function courseAnnual(){
        return $this->belongsTo('App\Models\CourseAnnual');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public static $rules = [
        "lecturer_id" => "required|numeric",
        "course_annual_id" => "required|numeric",
        "time_tp" => "required|numeric",
        "time_course" => "required|numeric",
        "time_td" => "required|numeric"
    ];
}
