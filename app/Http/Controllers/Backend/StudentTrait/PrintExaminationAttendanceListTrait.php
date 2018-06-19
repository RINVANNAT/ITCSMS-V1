<?php

namespace App\Http\Controllers\Backend\StudentTrait;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Gender;
use App\Models\StudentAnnual;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait PrintExaminationAttendanceListTrait
{
    public function request_print_examination_attendance_list() {
        $academicYears = AcademicYear::orderBy('id','desc')->lists('name_kh','id');
        $genders = Gender::lists('code','id');
        $current_date = Carbon::now()->format('d/m/Y');

        return view(
            'backend.studentAnnual.print.request_print_examination_attendance_list',
            compact('academicYears','genders','student_class','current_date')
        );
    }
    public function print_examination_attendance_list(Request $request) {
        $academic_year = $request->get("academic_year_id");
        $semester = $request->get("semester_id");
        $department_option = $request->get("department_option_id")=="null"?null:$request->get("department_option_id");
        $department = $request->get("department_id");
        $degree = $request->get("degree_id");
        $grade = $request->get("grade_id");
        $by_group = false;
        if($request->get("by_group") != null) {
            $by_group = true;
        }

        $number_student_per_class = $request->get("number_student_per_class");
        $order_by = $request->get("order_by");

        $studentAnnuals = StudentAnnual::select([
            'studentAnnuals.id',
            "promotions.name as promotion",
            'groups.code as group',
            'students.id_card',
            'students.name_kh',
            'students.dob as dob',
            'students.name_latin',
            'genders.code as gender',
            'departmentOptions.code as option',
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
            ->leftJoin('promotions', 'studentAnnuals.promotion_id','=','promotions.id')
            ->where('studentAnnuals.academic_year_id',$academic_year)
            ->whereNull('group_student_annuals.department_id')
            ->where(function($query) use($semester){
                $query->where("group_student_annuals.semester_id",$semester)->orWhereNull("group_student_annuals.semester_id");
            })
            ->where(function($query){
                $query->where('students.radie','=', false)->orWhereNull('students.radie');
            });
            //->whereNotIn('students.id',function($query) use ($academic_year){
            //    $query->select('redouble_student.student_id')->from('redouble_student')->where('redouble_student.academic_year_id','=',$academic_year);
            //});

        if(!empty($department_option)){
            $studentAnnuals = $studentAnnuals->where('departmentOptions.id',$department_option);
        }
        if(!empty($department)){
            $studentAnnuals = $studentAnnuals->where('departments.id',$department);
        }
        if(!empty($degree)){
            $studentAnnuals = $studentAnnuals->where('degrees.id',$degree);
        }
        if(!empty($grade)){
            $studentAnnuals = $studentAnnuals->where('grades.id',$grade);
        }
        if($order_by == "by_name") {
            $studentAnnuals = $studentAnnuals->orderBy("name_latin");
        } else {
            $studentAnnuals = $studentAnnuals->orderBy("id_card");
        }

            $studentAnnuals = $studentAnnuals->get()->toArray();
        $total_student = count($studentAnnuals);

        // Already validate from frontend: remainder must less than total class
        if($by_group) {
            $data = collect($studentAnnuals)->groupBy("group");
        } else {
            $total_class = (int) ($total_student/$number_student_per_class);
            $remainder = $total_student%$number_student_per_class;
            $data = [];
            $index = 0;
            for($i=0;$i<$remainder;$i++) {
                $per_class = array();
                for($j=1;$j<=($number_student_per_class+1);$j++) {
                    $record = $studentAnnuals[$index];
                    $record["index"] = $index+1;
                    $per_class[]= $record;
                    $index++;
                }
                $data[] = $per_class;
            }
            for($i=0;$i<($total_class-$remainder);$i++) {
                $per_class = array();
                for($j=1;$j<=$number_student_per_class;$j++) {
                    $record = $studentAnnuals[$index];
                    $record["index"] = $index+1;
                    $per_class[]= $record;
                    $index++;
                }
                $data[] = $per_class;
            }
        }

        $academic_year = AcademicYear::where("id",$academic_year)->first();
        $department = Department::where("id",$department)->first();

        return view("backend.studentAnnual.print.examination_attendance_list",compact("data","academic_year","semester","department"));
    }
}