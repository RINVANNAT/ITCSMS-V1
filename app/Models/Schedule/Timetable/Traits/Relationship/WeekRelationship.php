<?php

namespace App\Models\Schedule\Timetable\Traits\Relationship;

use App\Models\Semester;

/**
 * Class WeekRelationship
 * @package App\Models\Schedule\Timetable\Traits\Relationship
 */
trait WeekRelationship
{
    /**
     * Get associated with model Semester.
     *
     * @return mixed
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}