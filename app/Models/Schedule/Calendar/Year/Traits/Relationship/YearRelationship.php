<?php

namespace App\Models\Schedule\Calendar\Year\Traits\Relationship;

use App\Models\Schedule\Calendar\Event\Event;

/**
 * Class YearRelationship
 * @package App\Models\Schedule\Calendar\Year\Traits\Relationship
 */
trait YearRelationship
{
    /**
     * @return mixed
     */
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}