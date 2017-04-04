<?php

namespace App\Models\Schedule\Calendar;

use Illuminate\Database\Eloquent\Model;

class CategoryEvent extends Model
{
    /**
     * Get associated with Event model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
