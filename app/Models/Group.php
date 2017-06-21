<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Group
 * @package App\Models
 */
class Group extends Model
{
    protected $table = 'groups';

    public function studentAnnuals() {

        return $this->belongsToMany(StudentAnnual::class, 'group_student_annuals', 'student_annual_id', 'group_id');
    }

}
