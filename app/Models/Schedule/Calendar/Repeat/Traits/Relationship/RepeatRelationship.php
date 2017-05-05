<?php

namespace App\Models\Schedule\Calendar\Repeat\Traits\Relationship;

use App\Models\Schedule\Calendar\Event\Event;

/**
 * Class RepeatRelationship
 * @package App\Models\Schedule\Calendar\Repeat\Traits\Relationship
 */
trait RepeatRelationship
{
    /**
     * @return mixed
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}