<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use Illuminate\Support\Facades\Response;

/**
 * Class AjaxFilterTimetableController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait AjaxFilterTimetableController
{
    /**
     * Filter timetable.
     *
     * @return mixed
     */
    public function filter()
    {
        return Response::json(['status' => true, 'data' => request()->all()]);
    }

    /**
     * Filter courses sessions.
     *
     * @return mixed
     */
    public function filterCoursesSessions()
    {
        return Response::json(['status' => true, 'data' => request()->all()]);
    }
}