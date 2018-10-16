<?php

namespace App\Models\Schedule\Timetable\Traits\Relationship;

use App\Models\Schedule\Timetable\TimetableGroup;
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

    public function groups ()
    {
        return $this->belongsToMany(TimetableGroup::class, 'timetable_group_slots', 'slot_id', 'timetable_group_id');
    }
}