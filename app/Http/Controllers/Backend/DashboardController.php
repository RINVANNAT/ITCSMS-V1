<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\CourseAnnual;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */

    public function index()
    {
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $courses = null;

        if($employee != null){
            $last_year = AcademicYear::orderBy('id','DESC')->first();
            $courses = CourseAnnual::leftJoin('course_annual_classes', 'course_annual_classes.course_annual_id', '=', 'course_annuals.id')
                ->leftJoin('departments','course_annual_classes.department_id', '=', 'departments.id')
                ->leftJoin('degrees','course_annual_classes.degree_id', '=', 'degrees.id')
                ->leftJoin('grades','course_annual_classes.grade_id', '=', 'grades.id')
                ->where('academic_year_id',$last_year->id)
                ->where('employee_id',$employee->id)
                ->select([
                    'course_annuals.name_en',
                    'course_annuals.id',
                    DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class")
                ])
                ->get()
                ->toArray();
        }

        return view('backend.dashboard',compact('courses'))->withUser(access()->user());
    }
}