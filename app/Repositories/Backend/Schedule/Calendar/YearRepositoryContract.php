<?php

namespace App\Repositories\Backend\Schedule\Calendar;

/**
 * Interface YearRepositoryContract
 * @package App\Repositories\Backend\Schedule\Calendar
 */
interface YearRepositoryContract
{
    /**
     * Find year object by name.
     *
     * @param $name
     * @return mixed
     */
    public function findByYear($name);
}