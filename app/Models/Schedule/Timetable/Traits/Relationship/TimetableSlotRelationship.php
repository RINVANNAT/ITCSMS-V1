<?php

namespace App\Models\Schedule\Timetable\Traits\Relationship;

use App\Models\Room;
use App\Models\Schedule\Timetable\MergeTimetableSlot;
use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\Timetable;

/**
 * Class TimetableSlotRelationship
 * @package App\Models\Schedule\Timetable\Traits\Relationship
 */
trait TimetableSlotRelationship
{
    /**
     * Get associated with model Timetable.
     *
     * @return mixed
     */
    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    /**
     * Get associated with model Room.
     *
     * @return mixed
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get associated with model Slot.
     *
     * @return mixed
     */
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    /**
     * Get associated with merge timetable slot model.
     *
     * @return mixed
     */
    public function mergeTimetableSlot()
    {
        return $this->belongsTo(MergeTimetableSlot::class);
    }
}