<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    public $table = "examTypes";


    public $fillable = [
        "name_en",
        "name_kh",
        "name_fr",
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
        "name_en" => "string",
        "name_kh" => "string",
        "name_fr" => "string"
    ];

    public static $rules = [
        "name_en" => "Required",
        "name_kh" => "Required"
    ];
}
