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
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\DB;

trait AverageFinalYearTrait
{
    public function get_observation($student_id) {
        $observation = "";
        $student = Student::find($student_id);
        $history = null;
        $scholarship_list = null;
        foreach ($student->studentAnnuals as $studentAnnual) {
            if($studentAnnual->history != null && $history == null) {
                $history = $studentAnnual->history->name_en;
            }
            foreach($studentAnnual->scholarships as $scholarship){
                if($scholarship_list == null && $scholarship->code != 'Boursier Partielle') {
                    $scholarship_list = $scholarship->code;
                }
            }
        };
        if($student->redoubles != null) {
            foreach ($student->redoubles as $redouble) {
                $observation = $observation." ".$redouble->name_en;
            };
        }
        return preg_replace('/\s+/', ' ', $observation." ".$history." ".$scholarship_list);
    }
    /**
     * @return mixed
     */
    public function print_average_final_year($type){
        try {
            $department = null;
            $department_option = null;
            $degree = null;
            $academic_year = null;
            $department_id = $_GET["department_id"];
            $option_id = isset($_GET["option_id"]) ? null : $_GET["option_id"];
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
            foreach ($students as &$student) {
                try{
                    $student["observation"] = $this->get_observation($student['student_id']);
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }
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
            // step 2
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

            //dd($scores[24985]);
            if (count($student_by_groups) > 0) {
                if ($type == "show"){
                    return view('backend.studentAnnual.average_final_year', compact('student_by_groups','scores','department','department_option','degree','academic_year','department_id','option_id','degree_id','academic_year_id'));
                }else if ($type == "print") {
                    return PDF::loadView('backend.studentAnnual.print.average_final_year', compact('student_by_groups','scores','department','department_option','degree','academic_year'))->setPaper('a4')->stream();
                }
            } else {
                abort(404, 'Please provide us your message');
            }
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }

        return Excel::create('Average Final Year', function ($excel) use ($student_by_groups, $scores, $department, $department_option, $degree, $academic_year) {
            $excel->sheet('Sheet 1', function ($sheet) use ($student_by_groups, $scores, $department, $department_option, $degree, $academic_year) {
                $sheet->mergeCells('E3:G3');
                $sheet->mergeCells('E4:G4');
                $sheet->mergeCells('E5:G5');
                $sheet->mergeCells('E6:G6');
                $sheet->mergeCells('E7:F7');
                $sheet->mergeCells('G7:H7');
                $sheet->mergeCells('I7:J7');

                $sheet->cell('E3', function ($cell) {
                    $cell->setValue('Moyenne fin d\'etude');
                    $cell->setFontSize(15);
                    $cell->setAlignment('center');
                });

                $sheet->cell('E4', function ($cell) use ($department, $department_option) {
                    $cell->setValue('Département '.$department->name_fr.' '. ($department_option != null ? $department_option->name_fr : "") );
                    $cell->setFontSize(13);
                    $cell->setAlignment('center');
                });
                $sheet->cell('E5', function ($cell) use ($academic_year, $degree, $department) {
                    $cell->setValue('Classe: '.$degree->code.($degree->id == 1 ? '5' : '2').'-'.$department->code);
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');
                });
                $sheet->cell('E6', function ($cell) use ($academic_year) {
                    $cell->setValue('Année Scolaire('.$academic_year->name_latin.')');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');
                });
                $sheet->cell('E8', function ($cell) {
                    $cell->setValue('1ère année');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('G8', function ($cell) {
                    $cell->setValue('2ème année');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('I8', function ($cell) {
                    $cell->setValue('Moy. de Sortie');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('K8', function ($cell) {
                    $cell->setValue('Mention');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('L8', function ($cell) {
                    $cell->setValue('Observation');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('A9', function ($cell) {
                    $cell->setValue('No');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('B9', function ($cell) {
                    $cell->setValue('ID');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('C9', function ($cell) {
                    $cell->setValue('Noms et Prénoms');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('D9', function ($cell) {
                    $cell->setValue('Sexe');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('E9', function ($cell) {
                    $cell->setValue('Moy.(M1)');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('F9', function ($cell) {
                    $cell->setValue('GPA');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('G9', function ($cell) {
                    $cell->setValue('Moy.(M2)');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('H9', function ($cell) {
                    $cell->setValue('GPA');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('I9', function ($cell) {
                    $cell->setValue('(M1+M2)/2');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('J9', function ($cell) {
                    $cell->setValue('GPA');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });
                $sheet->cell('K9', function ($cell) {
                    $cell->setValue('de sotie');
                    $cell->setFontSize(10);
                    $cell->setAlignment('center');
                });

                $i = 1;
                $row = 10;

                $min_score_before_graduated = 100;
                $min_score_graduated = 100;
                $min_moy_score = 100;
                $max_score_before_graduated = 0;
                $max_score_graduated = 0;
                $max_moy_score = 0;
                foreach ($student_by_groups as $key => $student_by_group) {
                    $result = [];
                    foreach ($student_by_group as $key => $student_by_class) {
                        $lowest_score = 100;
                        if(is_numeric($key)) {
                            $result[$student_by_class["grade_id"]]["total_score"] = $scores[$student_by_class["id"]]["final_score"];
                            $result[$student_by_class["grade_id"]]["total_gpa"] = get_gpa($scores[$student_by_class["id"]]["final_score"]);
                            $result[$student_by_class["grade_id"]]["credit"] = 0;
                            foreach ($scores[$student_by_class["id"]] as $key=>$score) {
                                if(is_numeric($key)){
                                    $result[$student_by_class["grade_id"]]["credit"] += $score["credit"];
                                }
                            }
                        }
                    }
                    $sheet->cell('A' . $row, $i);
                    $sheet->cell('B'.$row, $student_by_group[0]['id_card']);
                    $sheet->cell('C'.$row, strtoupper($student_by_group[0]['name_latin']));
                    $sheet->cell('D'.$row, $student_by_group[0]['gender']);
                    $sheet->cell('L'.$row, $student_by_group[0]['observation']);

                    foreach ($result as $year => $score_each_year) {
                        if($lowest_score > $score_each_year["total_score"]) {
                            $lowest_score = $score_each_year["total_score"];
                        }
                        if($year == 4 || $year ==1) {
                            if(is_numeric($score_each_year["total_score"]) && $min_score_before_graduated>$score_each_year["total_score"]){
                                $min_score_before_graduated = $score_each_year["total_score"];
                            }
                            if(is_numeric($score_each_year["total_score"]) && $max_score_before_graduated<$score_each_year["total_score"]){
                                $max_score_before_graduated = $score_each_year["total_score"];
                            }
                        } else if($year == 5 || $year ==2) {
                            if(is_numeric($score_each_year["total_score"]) && $min_score_graduated>$score_each_year["total_score"]){
                                $min_score_graduated = $score_each_year["total_score"];
                            }
                            if(is_numeric($score_each_year["total_score"]) && $max_score_graduated<$score_each_year["total_score"]){
                                $max_score_graduated = $score_each_year["total_score"];
                            }
                        }

                        if ($year == 1 || $year == 4){
                            $sheet->cell('E'.$row, $score_each_year["total_score"]);
                            $sheet->cell('F'.$row, $score_each_year["total_gpa"]);
                        } else {
                            $sheet->cell('G'.$row, $score_each_year["total_score"]);
                            $sheet->cell('H'.$row, $score_each_year["total_gpa"]);
                        }
                    }

                    $final_average_score = 0;
                    foreach($result as $result_score) {
                        if(is_numeric($result_score["total_score"]) && is_numeric($final_average_score)) {
                            $final_average_score = $final_average_score + $result_score["total_score"];
                        } else {
                            $final_average_score = "N/A";
                        }
                    }
                    if(is_numeric($final_average_score)) {
                        $final_average_score = $final_average_score / 2;
                        $final_average_gpa = get_gpa($final_average_score);
                        $final_average_mention = get_french_mention($final_average_score);
                        if($lowest_score<50) {
                            $final_average_gpa = get_gpa($lowest_score);
                            $final_average_mention = get_french_mention($lowest_score);
                        }
                        if($min_moy_score>$final_average_score) {
                            $min_moy_score = $final_average_score;
                        }
                        if($max_moy_score<$final_average_score) {
                            $max_moy_score = $final_average_score;
                        }
                    } else {
                        $final_average_score = "N/A";
                        $final_average_gpa = "N/A";
                        $final_average_mention = "N/A";
                    }

                    $sheet->cell('I'.$row, is_numeric($final_average_score)?round($final_average_score,2):"N/A");
                    $sheet->cell('J'.$row, $final_average_gpa);
                    $sheet->cell('K'.$row, $final_average_mention);

                    $i++;
                    $row++;
                }

                $sheet->cell('D'.$row, 'Max');
                $sheet->cell('E'.$row, $max_score_before_graduated);
                $sheet->cell('F'.$row, get_gpa($max_score_before_graduated));
                $sheet->cell('G'.$row, $max_score_graduated);
                $sheet->cell('H'.$row, get_gpa($max_score_graduated));
                $sheet->cell('I'.$row, $max_moy_score);
                $sheet->cell('J'.$row, get_gpa($max_moy_score));

                $row++;

                $sheet->cell('D'.$row, 'Min');
                $sheet->cell('E'.$row, $min_score_before_graduated);
                $sheet->cell('F'.$row, get_gpa($min_score_before_graduated));
                $sheet->cell('G'.$row, $min_score_graduated);
                $sheet->cell('H'.$row, get_gpa($min_score_graduated));
                $sheet->cell('I'.$row, $min_moy_score);
                $sheet->cell('J'.$row, get_gpa($min_moy_score));

                $sheet->setBorder('E8:L8', 'thin');
                $sheet->setBorder('A9:L' . ($row-2), 'thin');

            });
        })->download('xls');
    }
}