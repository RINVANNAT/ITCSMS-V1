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
use App\Models\Gender;
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

        return view(
            'backend.studentAnnual.print.request_print_attestation',
            compact('academicYears','genders','student_class','current_date')
        );
    }

    /**
     * @param PrintTranscriptRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function print_attestation(PrintTranscriptRequest $request){
        $smis_server = Configuration::where("key","smis_server")->first();
        $semester = 1;
        $studentAnnualIds = json_decode($request->ids);

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
        if(isset($students[0]) && (($students[0]['degree_id'] == 1 && $students[0]['grade_id'] == 5) || ($students[0]['degree_id'] == 2 && $students[0]['grade_id'] == 2))) {
            $view = 'backend.studentAnnual.print.attestation';
        }
        /*return view($view,
            compact(
                'ranking_data',
                'scores',
                'students',
                'semester',
                'transcript_type',
                'issued_by',
                'issued_date',
                'issued_number',
                'smis_server'
            )
        );*/

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
                'smis_server'
            )
        )->stream();
    }
}