<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public $table = "positions";


    public $fillable = [
        "tittle",
        "description"
    ];

    public function Employee()
    {

        return $this->belongsToMany('App\Models\Employee', 'employee_position', 'employee_id', 'position_id');
    }
}
