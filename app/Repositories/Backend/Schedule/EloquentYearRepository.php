<?php

namespace App\Repositories\Backend\Schedule;
use App\Models\Schedule\Calendar\Year;

/**
 * Class EloquentYearRepository
 * @package App\Repositories\Backend\Schedule
 */
class EloquentYearRepository implements YearRepositoryContract
{
    /**
     * Find by year on Year model.
     *
     * @param $year
     * @return mixed
     */
    public function findByYear($year)
    {
        return Year::where('year', $year)->first();
    }
}
