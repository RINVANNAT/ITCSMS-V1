<?php

namespace App\Models\Schedule\Timetable\Traits\Relationship;

use App\Models\CourseSession;
use App\Models\Room;
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
     * Get associated with model CourseSession.
     *
     * @return mixed
     */
    public function courseSession()
    {
        return $this->belongsTo(CourseSession::class);
    }
}