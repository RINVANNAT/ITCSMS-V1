<?php

namespace App\Repositories\Backend\Schedule\Calendar;

use App\Models\Schedule\Calendar\Year\Year;

/**
 * Class EloquentYearRepository
 * @package App\Repositories\Backend\Schedule\Calendar
 */
class EloquentYearRepository implements YearRepositoryContract
{

    /**
     * Find year object by name.
     *
     * @param $name
     * @return mixed
     */
    public function findByYear($name)
    {
        if(Year::where('name', $name)->first() instanceof Year)
        {
            return Year::where('name', $name)->first();
        }
        return false;
    }

    public function find($id)
    {
        return Year::find($id);
    }
}