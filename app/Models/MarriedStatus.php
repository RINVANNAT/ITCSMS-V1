<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarriedStatus extends Model
{
    public $table = "marriedStatus";


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
        "name_en" => "Required",
        "name_kh" => "Required"
    ];
}
