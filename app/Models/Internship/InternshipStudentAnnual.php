<?php

namespace App\Models\Internship;

use Illuminate\Database\Eloquent\Model;

class InternshipStudentAnnual extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function internship(){
        return $this->belongsTo(Internship::class);
    }
}
