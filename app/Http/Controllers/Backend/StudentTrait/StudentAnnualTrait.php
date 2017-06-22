<?php

namespace App\Http\Controllers\Backend\StudentTrait;
use App\Http\Requests\Backend\Student\GenerateStudentGroupRequest;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Enum\SemesterEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Degree;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Models\Semester;

/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 6/21/17
 * Time: 5:34 PM
 */
trait StudentAnnualTrait
{


    public function formGenerateGroup(GenerateStudentGroupRequest $request) {


        $degrees = Degree::lists('name_en','id');
        $grades = Grade::lists('name_en', 'id');
        $departments = Department::where('parent_id', 11)->orderBy('id')->get();//lists('code', 'id');
        $academicYears = AcademicYear::orderBy('id', 'DESC')->lists('name_latin', 'id');
        $semesters = Semester::lists('name_en', 'id');
        $options = DepartmentOption::get();

        return view('backend.studentAnnual.includes.form_generate_student_group', compact('academicYears', 'departments', 'grades', 'degrees', 'semesters', 'options'));
    }

    public function getNumberStudent(Request $request)
    {

        $department_id = $request->department_id;
        $academic_year_id = $request->academic_year_id;
        $degree_id = $request->degree_id;
        $grade_id = $request->grade_id;
        $semester_id = $request->semester_id;

        $studentAnnuals = DB::table('studentAnnuals')
            ->join('students', 'students.id', '=', 'studentAnnuals.student_id')
            ->where([
                ['studentAnnuals.degree_id', $degree_id],
                ['studentAnnuals.grade_id', $grade_id],
                ['studentAnnuals.academic_year_id', $academic_year_id],
                ['studentAnnuals.degree_id', $degree_id],
            ]);


        $department = Department::where('id', $department_id)->first();


        if($department->is_vocational) {

            if($semester_id > SemesterEnum::SEMESTER_ONE) {

                $studentAnnuals = $studentAnnuals->where(function($query) {
                    $query->whereNull('students.radie')
                        ->orWhere('students.radie', '=', false);
                });
            }

            $studentAnnuals = $studentAnnuals->get();

        } else {

            if($semester_id > SemesterEnum::SEMESTER_ONE) {

                $studentAnnuals = $studentAnnuals->where(function($query) {
                    $query->whereNull('students.radie')
                        ->orWhere('students.radie', '=', false);
                });
            }
            $studentAnnuals = $studentAnnuals->where('studentAnnuals.department_id', $department->id)->get();
        }

        $deptOptions = DB::table('departmentOptions')
            ->where('department_id', $department->id)->get();

        return \Illuminate\Support\Facades\Response::json(['status' => true, 'count' => count($studentAnnuals), 'option' => $deptOptions]);

    }

}