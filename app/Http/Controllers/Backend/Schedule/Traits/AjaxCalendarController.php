<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Department;
use Illuminate\Support\Facades\Response;

/**
 * Class AjaxCalendarController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait AjaxCalendarController
{
    /**
     * @return mixed
     */
    public function getDepartments()
    {
        return Response::json([
           'status' => true,
            'data' => Department::all()
        ]);
    }
}