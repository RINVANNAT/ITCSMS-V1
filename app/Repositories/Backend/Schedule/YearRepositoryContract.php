<?php

namespace App\Repositories\Backend\Schedule;

/**
 * Interface YearRepositoryContract
 * @package App\Repositories\Backend\Schedule
 */
interface YearRepositoryContract
{
    /**
     * Find by year on Year model.
     *
     * @param $year
     * @return mixed
     */
    public function findByYear($year);
}
