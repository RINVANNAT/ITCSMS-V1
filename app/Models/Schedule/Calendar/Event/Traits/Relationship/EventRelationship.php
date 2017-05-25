<?php

namespace App\Models\Schedule\Calendar\Event\Traits\Relationship;
use App\Models\Department;
use App\Models\Schedule\Calendar\Repeat\Repeat;
use App\Models\Schedule\Calendar\Year\Year;

/**
 * Class EventRelationship
 * @package App\Models\Schedule\Calendar\Event\Traits\Relationship
 */
trait EventRelationship
{
    /**
     * @return mixed
     */
    public function years()
    {
        return $this->belongsToMany(Year::class);
    }

    /**
     * Get associated with Repeat model for each FIX Event.
     *
     * @return mixed
     */
    public function repeat()
    {
        return $this->belongsTo(Repeat::class);
    }

    /**
     * @return mixed
     */
    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }
}