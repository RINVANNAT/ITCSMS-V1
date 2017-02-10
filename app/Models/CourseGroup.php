<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseGroup extends Model
{
    public $table = "course_groups";


    public $fillable = [
        "group",
        "course_annual_id",
        "course_session_id",
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

    public function courseSession(){
        return $this->belongsTo('App\Models\CourseSession');
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
        "group" => "required|numeric"
    ];
}
