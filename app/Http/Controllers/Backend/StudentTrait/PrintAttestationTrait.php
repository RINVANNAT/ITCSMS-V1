<?php
/**
 * Created by PhpStorm.
 * User: imac-07
 * Date: 3/26/18
 * Time: 9:15 AM
 */

namespace App\Http\Controllers\Backend\StudentTrait;


use App\Http\Requests\Backend\Student\PrintTranscriptRequest;
use App\Models\AcademicYear;
use App\Models\DefineAverage;
use App\Models\Gender;
use App\Models\Student;
use App\Models\StudentAnnual;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use App\Models\Configuration;
use Illuminate\Support\Facades\DB;

trait PrintAttestationTrait
{
    /**
     * @param PrintTranscriptRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function request_print_attestation(PrintTranscriptRequest $request){
        $academicYears = AcademicYear::orderBy('id','desc')->lists('name_kh','id');
        $genders = Gender::lists('code','id');
        $current_date = Carbon::now()->format('d/m/Y');
        $academicYear = $request->academic_year;

        return view(
            'backend.studentAnnual.print.request_print_attestation',
            compact('academicYears','genders','student_class','current_date', 'academicYear')
        );
    }

    /**
     * @param PrintTranscriptRequest $request
     * @return mixed
     */
    public function request_print_attestation_data(PrintTranscriptRequest $request){
        $academic_year = $request->get('academic_year');
        $group = $request->get('group');
        $gender = $request->get('gender');
        $department_option = [];
        $department = [];
        $degree = [];
        $grade = [];
        $semester = 2;
        $student_class = json_decode($request->get('student_class'));
        if(!empty($student_class)){
            foreach($student_class as $element){
                if($element->department_option_id!=null){
                    $department_option[] = $element->department_option_id;
                }
                if($element->department_id!=null){
                    $department[] = $element->department_id;
                }
                if($element->degree_id != null){
                    $degree[] = $element->degree_id;
                }
                if($element->grade_id != null){
                    $grade[] = $element->grade_id;
                }
            }
        }

        // This will return all passed students in the given academic year/semester 1 as a collection
        $studentAnnuals = StudentAnnual::select([
            'studentAnnuals.id','groups.code as group','students.id as student_id','students.id_card','students.name_kh','students.dob as dob','students.name_latin', 'genders.code as gender', 'departmentOptions.code as option',
            DB::raw("CONCAT(degrees.code,grades.code,departments.code,\"departmentOptions\".code) as class")
        ])
            ->leftJoin('students','students.id','=','studentAnnuals.student_id')
            ->leftJoin('genders', 'students.gender_id', '=', 'genders.id')
            ->leftJoin('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->leftJoin('departmentOptions', 'studentAnnuals.department_option_id', '=', 'departmentOptions.id')
            ->leftJoin('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id')
            ->leftJoin('group_student_annuals', 'group_student_annuals.student_annual_id', '=', 'studentAnnuals.id')
            ->leftJoin('groups','groups.id','=','group_student_annuals.group_id')
            ->leftJoin('redouble_student', 'redouble_student.student_id','=','students.id')
            ->where('studentAnnuals.academic_year_id',$academic_year)
            ->whereNull('group_student_annuals.department_id')
            ->where(function($query) use($semester){
                $query->where("group_student_annuals.semester_id",$semester)->orWhereNull("group_student_annuals.semester_id");
            })
            ->where(function($query){
                $query->where('students.radie','=', false)->orWhereNull('students.radie');
            })
            ->whereNotIn('students.id',function($query) use ($academic_year){
                $query->select('redouble_student.student_id')->from('redouble_student')->where('redouble_student.academic_year_id','=',$academic_year);
            });
        if(empty($student_class)) {
            $studentAnnuals = $studentAnnuals->where(function($query) {
                $query->where([
                    ['studentAnnuals.degree_id', 1],
                    ['studentAnnuals.grade_id', 5]
                ])->orWhere([
                    ['studentAnnuals.degree_id', 2],
                    ['studentAnnuals.grade_id', 2]
                ]);
            });
        } else {
            if(!empty($department_option)){
                $studentAnnuals = $studentAnnuals->whereIN('departmentOptions.id',$department_option);
            }
            if(!empty($department)){
                $studentAnnuals = $studentAnnuals->whereIN('departments.id',$department);
            }
            if(!empty($degree)){
                $studentAnnuals = $studentAnnuals->whereIN('degrees.id',$degree);
            }
            if(!empty($grade)){
                $studentAnnuals = $studentAnnuals->whereIN('grades.id',$grade);
            }
            if($group != null){
                $studentAnnuals = $studentAnnuals->where('groups.code',$group);
            }
            if($gender != null){
                $studentAnnuals = $studentAnnuals->where('students.gender_id',$gender);
            }
        }

        $studentAnnuals = $studentAnnuals->get()->toArray();

        // Get printed transcript date
        $printed_transcripts = DB::table("printed_transcripts")
            ->where("academic_year_id",$academic_year)
            ->get();
        $printed_transcripts = collect($printed_transcripts)->groupBy("student_annual_id")->toArray();
        foreach ($studentAnnuals as &$student){
            $student['printed_transcript'] = "";
            if(isset($printed_transcripts[$student['id']])){
                foreach($printed_transcripts[$student['id']] as $transcript){
                    $transcript_date = Carbon::createFromFormat("Y-m-d H:i:s",$transcript->created_at)->toDayDateTimeString();
                    if($transcript->type == "year"){
                        $student['printed_transcript'] = $student['printed_transcript']."<span class='label label-primary'>".$transcript->type." | ".$transcript_date."</span><br/>";
                    } else {
                        $student['printed_transcript'] = $student['printed_transcript']."<span class='label label-success'>".$transcript->type." | ".$transcript_date."</span><br/>";
                    }


                }
            }
        }
        // Sort by multiple columns
        $studentAnnuals = collect($studentAnnuals)->sortBy(function($student){
            return sprintf('%-12s%s', $student['class'],$student['name_latin']);
        });
        $datatables =  app('datatables')->of($studentAnnuals)
            ->filter(function ($instance) use ($request) {
                $keyword = $request->get('search');
                if ($keyword != null and $keyword['value'] != "") {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request, $keyword) {
                        if(
                            Str::contains(strtolower($row['name_latin']), strtolower($keyword['value'])) ||
                            Str::contains(strtolower($row['id_card']), strtolower($keyword['value']))
                        ){
                            return true;
                        } else {
                            return false;
                        }
                    });
                }
            })
            ->addColumn('checkbox', function($student) {
                return '<input type="checkbox" checked class="checkbox" data-id='.$student["student_id"].'>';
            })
            ->addColumn('name', function($student) {
                return  $student["name_kh"]."<br/>".
                    strtoupper($student["name_latin"]);
            })
            ->editColumn('dob', function($student) {
                $dob = Carbon::createFromFormat('Y-m-d H:i:s',$student["dob"])->format('d/m/Y');
                return $dob;
            });
        /*->addColumn('action', function ($student) {
            $actions = '<button data-id='.$student["id"].' style="float: right" class="btn btn-block btn-default btn-sm btn-single-print"><i class="fa fa-print"></i> Print</button>';
            return  $actions;

        });*/
        return $datatables->make(true);
    }

    /**
     * @param PrintTranscriptRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function print_attestation(PrintTranscriptRequest $request){
        $smis_server = Configuration::where("key","smis_server")->first();
        $semester = 2;
        $studentIds = json_decode($request->ids);

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
            })
            ->where(function($query) {
                $query->where([
                    ['studentAnnuals.degree_id', 1],
                    ['studentAnnuals.grade_id', 4]
                ])
                    ->orWhere([
                        ['studentAnnuals.degree_id', 1],
                        ['studentAnnuals.grade_id', 5]
                    ])
                    ->orWhere([
                        ['studentAnnuals.degree_id', 2],
                        ['studentAnnuals.grade_id', 1]
                    ])
                    ->orWhere([
                        ['studentAnnuals.degree_id', 2],
                        ['studentAnnuals.grade_id', 2]
                    ]);
            })
            ->whereIN('students.id',$studentIds)
            ->orderBy('students.id_card','ASC')
            ->get()
            ->toArray();

        $students = collect($students);
        $student_by_groups = collect($students)->sortBy(function($student){
            return sprintf('%-12s%s',
                $student['class'],
                $student['name_latin']
            );
        })->groupBy("student_id");

        $transcript_type = $request->get("transcript_type");
        $issued_by = $request->get("issued_by");
        $issued_number = $request->get("issued_number");
        $issued_date = $request->get("issued_date");
        $scores = [];
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

                $student1Or4['academic_year_id'] = '';
                $student2Or5['academic_year_id'] = '';
                $department_id = '';

                foreach ($student_by_class as $item) {
                    $department_id = $item['department_id'];
                    if ($item['grade_id'] == 1 || $item['grade_id'] == 4) {
                        $student1Or4['academic_year_id'] = $item['academic_id'];
                    }else {
                        $student2Or5['academic_year_id'] = $item['academic_id'];
                    }
                }
                $passedScore14 = 50;
                $passedScore25 = 50;

                $passedScore14Obj = DefineAverage::where([
                    'academic_year_id' => $student1Or4['academic_year_id'],
                    'department_id' => $department_id,
                    'semester_id' => 0
                ])->first();

                if ($passedScore14Obj instanceof DefineAverage) {
                    $passedScore14 = $passedScore14Obj->value;
                }

                $passedScore25Obj = DefineAverage::where([
                    'academic_year_id' => $student2Or5['academic_year_id'],
                    'department_id' => $department_id,
                    'semester_id' => 0
                ])->first();

                if ($passedScore25Obj instanceof DefineAverage) {
                    $passedScore25 = $passedScore25Obj->value;
                }
                $student_by_class['passedScore14'] = $passedScore14;
                $student_by_class['passedScore25'] = $passedScore25;

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

        $ranking_data = [];
        $view = 'backend.studentAnnual.print.attestation';
        /*if(isset($students[0]) && (($students[0]['degree_id'] == 1 && $students[0]['grade_id'] == 5) || ($students[0]['degree_id'] == 2 && $students[0]['grade_id'] == 2))) {
            $view = 'backend.studentAnnual.print.attestation';
        }*/

        //return view($view,
        //    compact(
        //        'ranking_data',
        //        'scores',
        //        'student_by_groups',
        //        'semester',
        //        'transcript_type',
        //        'issued_by',
        //        'issued_date',
        //        'issued_number',
        //        'smis_server'
        //    )
        //);


        return SnappyPdf::loadView($view,
            compact(
                'ranking_data',
                'scores',
                'student_by_groups',
                'semester',
                'transcript_type',
                'issued_by',
                'issued_date',
                'issued_number',
                'smis_server'
            )
        )->stream();
    }
}