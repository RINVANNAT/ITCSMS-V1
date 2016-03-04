<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GdeGrade extends Model
{
    public $table = "gdeGrades";


    public $fillable = [
        "name_kh",
        "name_en",
        "create_uid",
        "write_uid"
    ];

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name_kh" => "string",
        "name_en" => "string"
    ];

    public static $rules = [
        "name_kh" => "Required",
        "name_en" => "Required"
    ];
}
