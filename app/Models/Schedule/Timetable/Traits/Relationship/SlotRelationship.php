<?php

namespace App\Models\Schedule\Timetable\Traits\Relationship;

use App\Models\Schedule\Timetable\TimetableSlot;

/**
 * Class SlotRelationship
 * @package App\Models\Schedule\Timetable\Traits\Relationship
 */
trait SlotRelationship
{
    /**
     * @return mixed
     */
    public function timetableSlots()
    {
        return $this->hasMany(TimetableSlot::class);
    }
}