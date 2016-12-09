<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Percentage extends Model
{
    public $table = "percentages";


    public $fillable = [
        "name",
        "percent",
        "percentage_type",
        "create_uid",
        "write_uid"
    ];


    public function score()
    {
        return $this->belongsToMany('Score', 'percentage_scores');
    }
}
