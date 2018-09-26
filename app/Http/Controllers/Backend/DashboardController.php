<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CourseAnnual;
use App\Models\Employee;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
class DashboardController extends Controller
{
    /**
     * Dashboard page.
     *
     * @return mixed
     */
    public function index()
    {
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $courses = null;
        $semesterIds = Semester::select('id')->pluck('id');
        $last_year = AcademicYear::orderBy('id', 'DESC')->pluck('id');

        if ($employee != null) {
            $courses = CourseAnnual::leftJoin('departments', 'course_annuals.department_id', '=', 'departments.id')
                ->leftJoin('degrees', 'course_annuals.degree_id', '=', 'degrees.id')
                ->leftJoin('grades', 'course_annuals.grade_id', '=', 'grades.id')
                ->leftJoin('semesters', 'course_annuals.semester_id', '=', 'semesters.id')
                ->leftJoin('departmentOptions', 'course_annuals.department_option_id', '=', 'departmentOptions.id')
                ->whereIn('course_annuals.academic_year_id', $last_year)
                ->whereIn('course_annuals.semester_id', $semesterIds)
                ->where('employee_id', $employee->id)
                ->with("courseAnnualClass")
                ->select([
                    'course_annuals.department_id',
                    'course_annuals.degree_id',
                    'course_annuals.grade_id',
                    'course_annuals.semester_id',
                    'course_annuals.academic_year_id',
                    'course_annuals.name_en',
                    'course_annuals.id',
                    'semesters.name_kh as semester',
                    'departmentOptions.code as option',
                    DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class")
                ])
                ->orderBy('course_annuals.academic_year_id', "DESC")
                ->orderBy('course_annuals.department_id', "ASC")
                ->orderBy('course_annuals.degree_id', "ASC")
                ->orderBy('course_annuals.grade_id', "ASC")
                ->orderBy('course_annuals.semester_id', "DESC")
                ->get()
                ->toArray();
        }

        return view('backend.dashboard', compact('courses'))->withUser(access()->user());
    }
}