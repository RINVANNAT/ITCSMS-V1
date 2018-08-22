<?php

namespace App\Http\Controllers\Backend\StudentTrait;
use App\Http\Requests\Backend\Student\PrintTranscriptRequest;
use App\Models\AcademicYear;
use App\Models\Configuration;
use App\Models\DefineAverage;
use App\Models\Gender;
use App\Models\PrintedTranscript;
use App\Models\StudentAnnual;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Created by PhpStorm.
 * User: thavorac
 * Date: 7/30/17
 * Time: 9:48 PM
 * Description: preview and print report + statistic
 */
trait PrintTranscriptTrait
{
    public function request_print_transcript(PrintTranscriptRequest $request){
        $academicYears = AcademicYear::orderBy('id','desc')->lists('name_kh','id');
        $genders = Gender::lists('code','id');
        $current_date = Carbon::now()->format('d/m/Y');
        $academicYearSelected = $request->academic_year;
        return view(
            'backend.studentAnnual.print.request_print_transcript',
            compact('academicYears','genders','student_class','current_date', 'academicYearSelected')
        );
    }
    public function request_print_transcript_data(PrintTranscriptRequest $request){
        $academic_year = $request->get('academic_year');
        $group = $request->get('group');
        $gender = $request->get('gender');
        $department_option = [];
        $department = [];
        $degree = [];
        $grade = [];
        $semester = 1;
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
            'studentAnnuals.id','groups.code as group','students.id_card','students.name_kh','students.dob as dob','students.name_latin', 'genders.code as gender', 'departmentOptions.code as option',
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
            return sprintf('%-12s%s', $student['class'], $student['name_latin']);
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
                return '<input type="checkbox" checked class="checkbox" data-id='.$student["id"].'>';
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
    public function print_transcript(PrintTranscriptRequest $request){
        $academic_year_id = null;
        $department_id = null;
        $paramSemester = $request->transcript_type;
        $semester_id = 1;
        if($paramSemester == 'year') {
            $semester_id = 0;
        }

        $smis_server = Configuration::where("key","smis_server")->first();
        $semester = 1;
        $studentAnnualIds = json_decode($request->ids);
        $photo = $request->photo;
        $is_back = $request->is_back;
        $is_front = $request->is_front;
        $is_certificate = $request->is_certificate;

        ($is_back == 'true' || $is_front == 'true' ? $is_certificate = 'true' : $is_certificate);
        $students  = StudentAnnual::select([
            'students.id_card',
            'students.name_kh',
            'students.name_latin',
            'students.dob',
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
            ->leftJoin('students','students.id','=','studentAnnuals.student_id')
            ->leftJoin('academicYears', 'studentAnnuals.academic_year_id', '=', 'academicYears.id')
            ->leftJoin('genders', 'students.gender_id', '=', 'genders.id')
            ->leftJoin('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->leftJoin('departmentOptions', 'studentAnnuals.department_option_id', '=', 'departmentOptions.id')
            ->leftJoin('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id')
            ->leftJoin('group_student_annuals', 'group_student_annuals.student_annual_id', '=', 'studentAnnuals.id')
            ->leftJoin('groups','groups.id','=','group_student_annuals.group_id')
            ->whereNull('group_student_annuals.department_id')
            ->where(function($query) use($semester){
                $query->where("group_student_annuals.semester_id",$semester)->orWhereNull("group_student_annuals.semester_id");
            })
            ->whereIn('studentAnnuals.id', $studentAnnualIds)
            ->orderBy('students.id_card','ASC')
            ->get()
            ->toArray();

        if (count($studentAnnualIds) >0){
            $studentAnnual = StudentAnnual::find($studentAnnualIds[0]);
            $academic_year_id = $studentAnnual->academic_year_id;
            $department_id = $studentAnnual->department_id;

            $passedScoreI = 50;
            $passedScoreII = 50;
            $passedScoreFinal = 50;

            $passedScoreI = DefineAverage::where([
                'academic_year_id' => $academic_year_id,
                'department_id' => $department_id,
                'semester_id' => 1
            ])->first();

            $passedScoreFinal = DefineAverage::where([
                'academic_year_id' => $academic_year_id,
                'department_id' => $department_id,
                'semester_id' => 0
            ])->first();

            if ($passedScoreFinal instanceof DefineAverage) {
                $passedScoreFinal = $passedScoreFinal->value;
            } else {
                $passedScoreFinal = 50;
            }

            if ($passedScoreI instanceof DefineAverage) {
                $passedScoreI = $passedScoreI->value;
            } else {
                $passedScoreI = 50;
            }

            if ($semester_id == 0) {
                $passedScoreII = DefineAverage::where([
                    'academic_year_id' => $academic_year_id,
                    'department_id' => $department_id,
                    'semester_id' => 2
                ])->first();
                if ($passedScoreII instanceof DefineAverage) {
                    $passedScoreII = $passedScoreII->value;
                } else {
                    $passedScoreII = 50;
                }
            }
        }

        $students = collect($students);
        $students = collect($students)->sortBy(function($student){
            return sprintf('%-12s%s',
                $student['class'],
                $student['name_latin']
            );
        });

        $transcript_type = $request->get("transcript_type");
        $issued_by = $request->get("issued_by");
        $issued_number = $request->get("issued_number");
        $issued_date = $request->get("issued_date");
        $scores = [];

        foreach($studentAnnualIds as $id){

            if($transcript_type == "semester1"){
                $scores[$id] = $this->getStudentScoreBySemester($id,1);
            } else {
                $scores[$id] = $this->getStudentScoreBySemester($id,null); // Full year
            }
        }
        $ranking_data = [];
        if(isset($students[0]) && $students[0]['degree_id'] == 1 && $students[0]['grade_id'] == 1 && $is_certificate == 'true') {
            $params = array(
                "department_id" => $students[0]['department_id'],
                "degree_id" => $students[0]['degree_id'],
                "grade_id" => $students[0]['grade_id'],
                "academic_year_id" => $students[0]['academic_id'],
                "semester_id" => "",
                "dept_option_id" => ""
            );
            $ranking_data = collect(json_decode($this->get_total_score_summary($params))->data)->keyBy("student_id_card");
            $view = 'backend.studentAnnual.print.foundation_certificate';
            return view($view,
                compact(
                    'ranking_data',
                    'scores',
                    'students',
                    'semester',
                    'transcript_type',
                    'issued_by',
                    'issued_date',
                    'issued_number',
                    'smis_server',
                    'photo',
                    'is_front',
                    'is_back',
                    'is_certificate',
                    'passedScoreI',
                    'passedScoreII',
                    'passedScoreFinal'
                )
            );
        } else {
            $view = 'backend.studentAnnual.print.transcript';
        }

        return SnappyPdf::loadView($view,
            compact(
                'ranking_data',
                'scores',
                'students',
                'semester',
                'transcript_type',
                'issued_by',
                'issued_date',
                'issued_number',
                'smis_server',
                'photo',
                'is_front',
                'is_back',
                'is_certificate',
                'passedScoreI',
                'passedScoreII',
                'passedScoreFinal'
            )
        )
        ->setOption('margin-bottom', 0)
        ->setOption('margin-left', 0)
        ->setOption('margin-right', 0)
        ->setOption('margin-top', 0)
        ->setOption('encoding', 'utf-8')
        ->stream();
    }

    public function mark_printed_transcript(PrintTranscriptRequest $request){

        $studentAnnualIds = json_decode($request->ids);
        $type = $request->transcript_type;

        $date = Carbon::now()->addHours(7);
        $student = StudentAnnual::find($studentAnnualIds[0]);
        $academic_year_id = $student->academic_year_id;

        $success = true;
        foreach($studentAnnualIds as $student_id) {
            if(!PrintedTranscript::create([
                "student_annual_id"=> $student_id,
                "type" => $type,
                "academic_year_id" => $academic_year_id,
                "create_uid" => auth()->user()->id,
                "created_at" => $date
            ])){
                $success = false;
            };
        }

        if($success){
            return response()->json(['status' => 'success', 'message' => 'All printed transcripts are marked']);
        } else {
            return response()->json(['status' => 'fail', 'state' => 'Something went wrong! please try again or contact administrator']);
        }
    }
}
