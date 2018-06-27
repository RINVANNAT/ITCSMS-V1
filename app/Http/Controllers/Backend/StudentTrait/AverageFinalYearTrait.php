<?php
/**
 * Created by PhpStorm.
 * User: imac-07
 * Date: 3/27/18
 * Time: 10:34 AM
 */

namespace App\Http\Controllers\Backend\StudentTrait;

use App\Models\AcademicYear;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Student;
use App\Models\StudentAnnual;
use PDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\DB;

trait AverageFinalYearTrait
{
    /**
     * @return mixed
     */
    public function print_average_final_year($type){
        $department = null;
        $department_option = null;
        $degree = null;
        $academic_year = null;
        if($_GET["department_id"] != "") {
            $department = Department::find($_GET["department_id"]);
        }
        if($_GET["option_id"] != "") {
            $department_option = DepartmentOption::find($_GET["option_id"]);
        }
        if($_GET["degree_id"] != "") {
            $degree = Degree::find($_GET["degree_id"]);
        }
        if($_GET["academic_year_id"] != "") {
            $academic_year = AcademicYear::find($_GET["academic_year_id"]);
        }
        $semester = 2;

        $students  = Student::select([
            'students.id_card',
            'students.name_kh',
            'students.name_latin',
            'students.dob',
            'students.id as student_id',
            'departments.name_kh as department',
            'students.photo',
            'studentAnnuals.id',
            'studentAnnuals.department_id',
            'studentAnnuals.degree_id',
            'studentAnnuals.grade_id',
            'studentAnnuals.academic_year_id',
            'studentAnnuals.id',
            'departments.name_kh as department_kh',
            'departments.name_en as department_en',
            'departments.name_fr as department_fr',
            'departmentOptions.name_en as option_en',
            'departmentOptions.name_fr as option_fr',
            'departmentOptions.name_kh as option_kh',
            'degrees.name_en as degree_en',
            'degrees.name_fr as degree_fr',
            'degrees.name_kh as degree_kh',
            'grades.name_en as grade_en',
            'grades.name_fr as grade_fr',
            'grades.name_kh as grade_kh',
            'academicYears.id as academic_id',
            'academicYears.name_kh as academic_year_kh',
            'academicYears.name_latin as academic_year_latin',
            'genders.code as gender',
            'groups.code as group',
            DB::raw("CONCAT(degrees.code,grades.code,departments.code,\"departmentOptions\".code) as class")
        ])
            ->leftJoin('studentAnnuals','students.id','=','studentAnnuals.student_id')
            ->leftJoin('academicYears', 'studentAnnuals.academic_year_id', '=', 'academicYears.id')
            ->leftJoin('genders', 'students.gender_id', '=', 'genders.id')
            ->leftJoin('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->leftJoin('departmentOptions', 'studentAnnuals.department_option_id', '=', 'departmentOptions.id')
            ->leftJoin('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id')
            ->leftJoin('group_student_annuals', 'group_student_annuals.student_annual_id', '=', 'studentAnnuals.id')
            ->leftJoin('groups','groups.id','=','group_student_annuals.group_id')
            ->where(function($query){
                $query->where('students.radie','=', false)->orWhereNull('students.radie');
            })
            ->whereNull('group_student_annuals.department_id')
            ->whereIN("studentAnnuals.academic_year_id",[$academic_year->id,$academic_year->id-1])
            ->where(function($query) use($semester){
                $query->where("group_student_annuals.semester_id",$semester)->orWhereNull("group_student_annuals.semester_id");
            });

            if($department_option != null){
                $students = $students->where('studentAnnuals.department_option_id',"=",$department_option->id);
            }
            if($department != null){
                $students = $students->where('studentAnnuals.department_id',"=",$department->id);
            }
            if($degree != null){
                $students = $students->where('studentAnnuals.degree_id',"=",$degree->id);
            }
            $students = $students->where(function($query) {
                $query->where('studentAnnuals.grade_id',"=", 4)
                ->orWhere('studentAnnuals.grade_id',"=", 5)
                ->orWhere('studentAnnuals.grade_id',"=", 1)
                ->orWhere('studentAnnuals.grade_id', 2);
            });

            $students = $students->orderBy('students.id_card','ASC')
            ->get()
            ->toArray();

        $students = collect($students);
        $student_by_groups = collect($students)->sortBy(function($student){
            return sprintf('%-12s%s',
                $student['class'],
                $student['name_latin']
            );
        })->groupBy("student_id");

        $scores = [];

        foreach($student_by_groups as $student_by_class){
            if(count($student_by_class) == 2) {
                foreach($student_by_class as $student_by_grade) {
                    $scores[$student_by_grade["id"]] = $this->getStudentScoreBySemester($student_by_grade['id'],null); // Full year
                }
            } else {
                // Something wrong here. It suppose to have only 2
                //dd($student_by_class);
                //throw new \Exception('Students have multiple class record');
            }
        }

        if ($type == "show"){
            return view('backend.studentAnnual.average_final_year', compact('student_by_groups','scores','department','department_option','degree','academic_year'));
        }
        return PDF::loadView('backend.studentAnnual.print.average_final_year')->setPaper('a4')->stream();
    }
}