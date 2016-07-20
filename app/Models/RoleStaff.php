<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleStaff extends Model
{
    public $table = "roleStaffs";

    public $fillable = [
	    "name",
		"description"
	];
}
