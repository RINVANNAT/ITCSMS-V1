<?php

namespace App\Repositories\Backend\Schedule;

/**
 * Interface EventRepositoryContract
 * @package App\Repositories\Backend\Schedule
 */
interface EventRepositoryContract
{
    public function getAllObjectEvents();

    /**
     * Find all events that match with year.
     *
     * @param $id
     * @return mixed
     */
    public function getEventsByYear($id);
}
