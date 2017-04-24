<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;
use Illuminate\Support\Facades\Response;

/**
 * Class AjaxCloneTimetableController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait AjaxCloneTimetableController
{
    /**
     * @return mixed
     */
    public function cloneTimetable()
    {
        return Response::json(['status'=>true, 'data'=>request()->all()]);
    }
}