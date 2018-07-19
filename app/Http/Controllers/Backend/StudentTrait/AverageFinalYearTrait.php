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
        $department_id = $_GET["department_id"];
        $option_id = $_GET["option_id"];
        $degree_id= $_GET["degree_id"];
        $academic_year_id = $_GET["academic_year_id"];

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

        $graduated_student_ids = Student::select([
                "students.id"
            ])
            ->leftJoin('studentAnnuals','students.id','=','studentAnnuals.student_id')
            ->leftJoin('academicYears', 'studentAnnuals.academic_year_id', '=', 'academicYears.id')
            ->leftJoin('group_student_annuals', 'group_student_annuals.student_annual_id', '=', 'studentAnnuals.id')
            ->where(function($query){
                $query->where('students.radie','=', false)->orWhereNull('students.radie');
            })
            ->whereNull('group_student_annuals.department_id')
            ->where("studentAnnuals.academic_year_id","=",$academic_year->id)
            ->where(function($query) use($semester){
                $query->where("group_student_annuals.semester_id",$semester)->orWhereNull("group_student_annuals.semester_id");
            });

        if($department_option != null){
            $graduated_student_ids = $graduated_student_ids->where('studentAnnuals.department_option_id',"=",$department_option->id);
        }
        if($department != null){
            $graduated_student_ids = $graduated_student_ids->where('studentAnnuals.department_id',"=",$department->id);
        }
        if($degree != null){
            $graduated_student_ids = $graduated_student_ids->where('studentAnnuals.degree_id',"=",$degree->id);
        }
        $graduated_student_ids = $graduated_student_ids->where(function($query) {
            $query->where('studentAnnuals.grade_id',"=", 5)
                ->orWhere('studentAnnuals.grade_id',"=", 2);
        })->pluck("id");

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
            //->whereIN("studentAnnuals.academic_year_id",[$academic_year->id,$academic_year->id-1])
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
            })->whereIN('students.id',$graduated_student_ids);

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
        // Clean redouble student record first
        foreach($student_by_groups as &$student_by_class){
            $before_graduated_year = null;
            $before_graduated_key = null;
            $graduated_year = null;
            $graduated_key = null;
            if(count($student_by_class) > 2) {
                foreach($student_by_class as $key => $student_by_grade) {
                    if($student_by_grade['grade_id'] == 4 || $student_by_grade['grade_id']==1){
                        if($before_graduated_year !== null) {
                            // already exist, compare which one is smaller then remove
                            if($before_graduated_year>$student_by_grade['academic_year_id']){
                                $student_by_class->forget($key);
                            } else {
                                $student_by_class->forget($before_graduated_key);
                            }
                        } else {
                            $before_graduated_key = $key;
                            $before_graduated_year = $student_by_grade['academic_year_id'];
                        }
                    } else if ($student_by_grade['grade_id'] == 5 || $student_by_grade['grade_id']==2) {
                        if($graduated_year !== null) {
                            // already exist, compare which one is smaller then remove
                            if($graduated_year>$student_by_grade['academic_year_id']){
                                $student_by_class->forget($key);
                            } else {
                                $student_by_class->forget($graduated_key);
                            }
                        } else {
                            $graduated_key = $key;
                            $graduated_year = $student_by_grade['academic_year_id'];
                        }
                    }
                }
            }
        }
        $errors = [];
        foreach($student_by_groups as &$student_by_class){
            $moy_score = 0;
            if(count($student_by_class) == 2) {
                foreach($student_by_class as $student_by_grade) {
                    $scores[$student_by_grade["id"]] = $this->getStudentScoreBySemester($student_by_grade['id'],null); // Full year
                    if(empty($scores[$student_by_grade["id"]])) {
                        $scores[$student_by_grade["id"]] = array("final_score" => "N/A","final_score_s1" => "N/A","final_score_s2" => "N/A");
                        $moy_score = "N/A";
                    }
                    if(is_numeric($moy_score)){
                        $moy_score = $moy_score + $scores[$student_by_grade["id"]]["final_score"];
                    }
                }
            } else {
                // Something wrong here. It suppose to have only 2
                array_push($errors,array("count"=>count($student_by_class), "id" => $student_by_class));
                //throw new \Exception('Students have multiple class record');
            }
            if(is_numeric($moy_score)) {
                $student_by_class->put("moy_score",$moy_score/2);
            } else {
                $student_by_class->put("moy_score",$moy_score);
            }
        }

        $student_by_groups = $student_by_groups->sortByDesc(function($collection){
            return $collection->get("moy_score");
        });

        if ($type == "show"){
            return view('backend.studentAnnual.average_final_year', compact('student_by_groups','scores','department','department_option','degree','academic_year','department_id','option_id','degree_id','academic_year_id'));
        }
        return PDF::loadView('backend.studentAnnual.print.average_final_year', compact('student_by_groups','scores','department','department_option','degree','academic_year'))->setPaper('a4')->stream();
    }
}