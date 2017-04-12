<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Controller;

class TimetableController extends Controller
{
    public function index()
    {
        return view('backend.schedule.timetables.index');
    }
}
