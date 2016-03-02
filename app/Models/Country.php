<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $table = "countries";


    public $fillable = [
        "name",
        "name_kh",
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
        "name" => "string",
        "name_kh" => "string"
    ];

    public static $rules = [
        "name" => "Required",
        "name_kh" => "Required"
    ];
}
