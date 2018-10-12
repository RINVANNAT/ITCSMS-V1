<?php

namespace App\Models\Schedule\Timetable\Traits\Relationship;

use App\Models\Employee;
use App\Models\Room;
use App\Models\Schedule\Timetable\MergeTimetableSlot;
use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableGroup;

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

    /**
     * @return mixed
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'lecturer_id');
    }

    /**
     * @return mixed
     */
    public function groups ()
    {
        return $this->belongsToMany(TimetableGroup::class, 'timetable_group_sessions', 'timetable_slot_id', 'timetable_group_id');
    }
}