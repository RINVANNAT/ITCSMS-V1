<?php

namespace App\Models\Schedule\Calendar;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * Get associated with Category Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoryEvent()
    {
        return $this->belongsTo(CategoryEvent::class);
    }

    /**
     * Get associated with Year model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function years()
    {
        return $this->belongsToMany(Year::class);
    }
}
