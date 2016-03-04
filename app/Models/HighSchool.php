<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HighSchool extends Model
{
    public $table = "highSchools";


    public $fillable = [
        "id",
        "name_kh",
        "name_en",
        "province_id",
        "d_id",
        "c_id",
        "v_id",
        "s_id",
        "ps_id",
        "prefix_id",
        "valid",
        "is_no_school",
        "locp_code",
        "locd_code",
        "locc_code",
        "locv_code"
    ];

    public $timestamps = false;

    public function student_bac2s(){
        return $this->hasMany('App\Models\StudentBac2');
    }
    public function students(){
        return $this->hasMany('App\Models\Student');
    }
    public function candidates(){
        return $this->hasMany('App\Models\Candidate');
    }
    public function province(){
        return $this->belongsTo('App\Models\Origin','province_id');
    }
    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }
}
