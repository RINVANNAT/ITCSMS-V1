<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PercentageScore extends Model
{
    //

    public $table = "percentage_scores";

    public $fillable = [
        "percentage_id",
        "score_id"
    ];
}
