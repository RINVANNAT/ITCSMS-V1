<?php

namespace App\Models\Schedule\Timetable;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MergeTimetableSlot
 * @package App\Models\Schedule\Timetable
 */
class MergeTimetableSlot extends Model
{
    /**
     * Assigned with merge_timetable_slots table.
     *
     * @var string
     */
    protected $table = 'merge_timetable_slots';

    /**
     * Get associated with Timetable Slot model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timetableSlot()
    {
        return $this->belongsTo(TimetableSlot::class);
    }
}