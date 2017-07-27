<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Backend\Schedule\Traits\ViewTimetableByTeacherController;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CourseAnnual;
use App\Models\Employee;
use App\Models\Schedule\Timetable\TimetableSlot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
class DashboardController extends Controller
{
    use ViewTimetableByTeacherController;

    /**
     * Dashboard page.
     *
     * @return mixed
     */
    public function index()
    {
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $courses = null;

        if ($employee != null) {
            $last_year = AcademicYear::orderBy('id', 'DESC')->first();
            $courses = CourseAnnual::leftJoin('departments', 'course_annuals.department_id', '=', 'departments.id')
                ->leftJoin('degrees', 'course_annuals.degree_id', '=', 'degrees.id')
                ->leftJoin('grades', 'course_annuals.grade_id', '=', 'grades.id')
                ->leftJoin('semesters', 'course_annuals.semester_id', '=', 'semesters.id')
                ->leftJoin('departmentOptions', 'course_annuals.department_option_id', '=', 'departmentOptions.id')
                ->where('course_annuals.academic_year_id', $last_year->id)
                ->where('employee_id', $employee->id)
                ->with("courseAnnualClass")
                ->select([
                    'course_annuals.department_id',
                    'course_annuals.degree_id',
                    'course_annuals.grade_id',
                    'course_annuals.semester_id',
                    'course_annuals.name_en',
                    'course_annuals.id',
                    'semesters.name_kh as semester',
                    'departmentOptions.code as option',
                    DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class")
                ])
                ->orderBy('course_annuals.department_id', "ASC")
                ->orderBy('course_annuals.degree_id', "ASC")
                ->orderBy('course_annuals.grade_id', "ASC")
                ->orderBy('course_annuals.semester_id', "ASC")
                ->get()
                ->toArray();
        }

        /** @var Collection $timetables */
        $timetables = new Collection();
        $timetable_slots = TimetableSlot::where(['teacher_name' => auth()->user()->name])->get();

        foreach ($timetable_slots as $timetable_slot) {
            $timetables->push($timetable_slot->timetable);
        }
        $timetables = $timetables->keyBy('id');


        /*if (count($timetables) > 0) {
            $timetables = $timetables->keyBy('id');

            $academicYears = new Collection();
            $departments = new Collection();
            $degrees = new Collection();
            $grades = new Collection();
            $options = new Collection();
            $semesters = new Collection();
            $weeks = new Collection();
            $groups = new Collection();

            foreach ($timetables as $timetable) {

                $academicYears->push($timetable->academic_year_id);
                $departments->push($timetable->department_id);
                $degrees->push($timetable->degree_id);
                $grades->push($timetable->grade_id);
                $timetable->option_id == null ? null : $options->push($timetable->option_id);
                $semesters->push($timetable->semester_id);
                $weeks->push($timetable->week_id);
                $timetable->group_id == null ? null : $groups->push($timetable->group_id);

            }

            $academicYears = array_unique($academicYears->toArray());
            $departments = array_unique($departments->toArray());
            $degrees = array_unique($degrees->toArray());
            $grades = array_unique($grades->toArray());
            $options = array_unique($options->toArray());
            $semesters = array_unique($semesters->toArray());
            $weeks = array_unique($weeks->toArray());
            $groups = array_unique($groups->toArray());

            $array_academic_year = array();
            $array_departments = array();
            $array_degrees = array();
            $array_grades = array();
            $array_options = array();
            $array_semesters = array();
            $array_weeks = array();
            $array_groups = array();

            if (count($academicYears) > 0) {
                foreach ($academicYears as $academicYear) {
                    array_push($array_academic_year, AcademicYear::find($academicYear));
                }
            }

            if (count($departments) > 0) {
                foreach ($departments as $department) {
                    array_push($array_departments, Department::find($department));
                }
            }

            if (count($degrees) > 0) {
                foreach ($degrees as $degree) {
                    array_push($array_degrees, Degree::find($degree));
                }
            }

            if (count($grades) > 0) {
                foreach ($grades as $grade) {
                    array_push($array_grades, Grade::find($grade));
                }
            }

            if (count($options) > 0) {
                foreach ($options as $option) {
                    array_push($array_options, DepartmentOption::find($option));
                }
            }

            if (count($semesters) > 0) {
                foreach ($semesters as $semester) {
                    array_push($array_semesters, Semester::find($semester));
                }
            }

            if (count($weeks) > 0) {
                foreach ($weeks as $week) {
                    array_push($array_weeks, Week::find($week));
                }
            }

            if (count($groups) > 0) {
                foreach ($groups as $group) {
                    array_push($array_groups, Group::find($group));
                }
            }

            if (count($array_groups) > 0) {
                usort($array_groups, function ($a, $b) {
                    if (is_numeric($a->code)) {
                        return $a->code - $b->code;
                    } else {
                        return strcmp($a->code, $a->code);
                    }
                });
            }

            return view('backend.dashboard', compact('courses'))
                ->with([
                    'academicYears' => $array_academic_year,
                    'departments' => $array_departments,
                    'degrees' => $array_degrees,
                    'grades' => $array_grades,
                    'options' => $array_options,
                    'semesters' => $array_semesters,
                    'weeks' => $array_weeks,
                    'groups' => $array_groups
                ])
                ->withUser(access()->user());
        }*/
        return view('backend.dashboard', compact('courses', 'timetables'))->withUser(access()->user());
    }
}