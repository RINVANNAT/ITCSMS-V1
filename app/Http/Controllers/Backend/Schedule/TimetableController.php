<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Degree;
use App\Models\Grade;

/**
 * Class TimetableController
 * @package App\Http\Controllers\Backend\Schedule
 */
class TimetableController extends Controller
{
    /**
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $academicYears = AcademicYear::all();
        $degrees = Degree::all();
        $grades = Grade::all();

        return view('backend.schedule.timetables.index',
            compact(
                'academicYears',
                'degrees',
                'grades'
            )
        );
    }
}
