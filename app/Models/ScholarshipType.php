<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipType extends Model
{
    public $table = "scholarshipTypes";


    public $fillable = [
        "name_kh",
        "name_en",
        "name_fr",
        "create_uid",
        "write_uid"
    ];

    public function creator(){
        return $this->belongsTo('App\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\User','write_uid');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name_kh" => "string",
        "name_en" => "string",
        "name_fr" => "string"
    ];

    public static $rules = [
        "name_kh" => "Required",
        "name_en" => "Required"
    ];
}
