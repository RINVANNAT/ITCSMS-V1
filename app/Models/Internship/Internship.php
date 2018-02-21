<?php

namespace App\Models\Internship;

use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function internship_student_annuals(){
        return $this->hasMany(InternshipStudentAnnual::class);
    }
}
