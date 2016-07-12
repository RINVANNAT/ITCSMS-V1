<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempEmployee extends Model
{
   public $table = "tempEmployees";

    public $fillable = [
	    "name_kh",
		"name_latin",
		"birthdate",
		"address",
        "email",
        "phone"
        "gender_id"
	];
}
