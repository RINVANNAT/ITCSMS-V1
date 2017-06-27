<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Origin;
use App\Models\Semester;
use App\Models\StudentAnnual;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class VocationalStudentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::where('is_vocational',true)->orderBy('id','DESC')->lists('code','id'); // 11 is for all academic departments
        $degrees = Degree::lists('name_kh','id');
        $grades = Grade::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');
        $options = DepartmentOption::lists('code','id');
        $academicYears = AcademicYear::orderBy('id','desc')->lists('name_kh','id');
        $origins = Origin::lists('name_kh','id');
        $semesters = Semester::lists('name_kh','id');

        return view('backend.vocational_student.index',compact('departments','degrees','grades','genders','options','academicYears','origins','semesters'));
    }

    public function data(Request $request)
    {
        if ($academic_year = $request->get('academic_year')) {
            // do nothing here
        } else {
            $academic_year =AcademicYear::orderBy('id','desc')->first()->id;
        }

        $studentAnnuals = StudentAnnual::select([
            'studentAnnuals.id','groups.code as group','students.id_card','students.name_kh','students.dob as dob','students.name_latin', 'genders.code as gender', 'departmentOptions.code as option',
            DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class")
        ])
            ->leftJoin('students','students.id','=','studentAnnuals.student_id')
            ->leftJoin('genders', 'students.gender_id', '=', 'genders.id')
            ->leftJoin('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->leftJoin('departmentOptions', 'studentAnnuals.department_option_id', '=', 'departmentOptions.id')
            ->leftJoin('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id')
            ->leftJoin('group_student_annuals', 'group_student_annuals.student_annual_id', '=', 'studentAnnuals.id')
            ->leftJoin('groups','groups.id','=','group_student_annuals.group_id');

        if ($scholarship = $request->get('scholarship')) {
            $studentAnnuals->leftJoin('scholarship_student_annual', 'studentAnnuals.id', '=', 'scholarship_student_annual.student_annual_id');
        }

        if($redouble = $request->get('redouble')){
            if($redouble == "with") { // with redouble this year
                // do nothing here
            } else if ($redouble == "no"){ // without redouble this year
                $studentAnnuals->leftJoin('redouble_student', 'redouble_student.student_id','=','students.id')
                    ->whereNotIn('students.id',function($query) use ($academic_year){
                        $query->select('redouble_student.student_id')->from('redouble_student')->where('redouble_student.academic_year_id','=',$academic_year);
                    });
            } else {  // only redouble student this year
                $studentAnnuals->join('redouble_student', 'redouble_student.student_id','=','students.id')
                    ->where('redouble_student.academic_year_id','=',$academic_year);
            }
        }

        $datatables = app('datatables')->of($studentAnnuals)
            ->editColumn('name_latin',function($studentAnnual){
                return strtoupper($studentAnnual->name_latin);
            })
            ->editColumn('dob', function ($studentAnnual){
                $date = Carbon::createFromFormat("Y-m-d h:i:s",$studentAnnual->dob);
                return $date->toFormattedDateString();
            })
            ->addColumn('action', function ($studentAnnual) {
                $date = Carbon::createFromFormat("Y-m-d h:i:s",$studentAnnual->dob);

                $data = array();
                $object = new \stdClass();
                $object->id = $studentAnnual->id;
                $object->id_card = $studentAnnual->id_card;
                $object->name_kh = $studentAnnual->name_kh;
                $object->name_latin = $studentAnnual->name_latin;
                $object->dob = $date->toFormattedDateString();
                $object->gender = $studentAnnual->gender;
                $object->class = $studentAnnual->class;
                $object->option = $studentAnnual->option;

                $data[] = $object;

                $actions = "";
                if(Auth::user()->allow('edit-students')){
                    $actions = $actions. ' <a href="' . route('admin.studentAnnuals.edit', $studentAnnual->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a>';
                }
                if(Auth::user()->allow('delete-students')){
                    $actions = $actions. ' <button class="btn btn-xs btn-danger btn-delete" data-remote="' . route('admin.studentAnnuals.destroy', $studentAnnual->id) . '"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                }


                $actions = $actions.' <button class="btn btn-xs btn-info btn-show" data-remote="' . route('admin.studentAnnuals.show', $studentAnnual->id) . '"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.view') . '"></i></button>' .
                    " <button class='btn btn-xs btn-export' style='display:none' data-remote='" .
                    json_encode($data)  .
                    "'><i class='fa fa-external-link-square' data-toggle='tooltip' data-placement='top' title='" . 'export' . "'></i></button>" ;
                return $actions;
            });

        // additional search
        $semester = $datatables->request->get('semester');
        $datatables->where('studentAnnuals.academic_year_id', '=', $academic_year)
            ->where(function($query) use($semester){
                $query->where("group_student_annuals.semester_id",$semester)->orWhereNull("group_student_annuals.semester_id");
            });

        if ($degree = $datatables->request->get('degree')) {
            $datatables->where('studentAnnuals.degree_id', '=', $degree);
        }
        if ($grade = $datatables->request->get('grade')) {
            $datatables->where('studentAnnuals.grade_id', '=', $grade);
        }
        if ($department = $datatables->request->get('department')) {
            $datatables->where('group_student_annuals.department_id', '=', $department);
        }
        if ($gender = $datatables->request->get('gender')) {
            $datatables->where('students.gender_id', '=', $gender);
        }
        if ($option = $datatables->request->get('option')) {
            $datatables->where('studentAnnuals.department_option_id', '=', $option);
        }
        if ($origin = $datatables->request->get('origin')) {
            $datatables->where('students.origin_id', '=', $origin);
        }
        if ($group = $datatables->request->get('group')) {
            $datatables->where('groups.code', '=', $group);
        }
        if ($scholarship = $datatables->request->get('scholarship')) {
            $datatables->where('scholarship_student_annual.scholarship_id', '=', $scholarship);
        }
        if ($radie = $datatables->request->get('radie')) {
            if($radie == "with") { // return all student include radie
                // do nothing here
            } else if($radie == "no") { // return only student without radie
                $datatables->where(function($query){
                    $query->where('students.radie','=', false)->orWhereNull('students.radie');
                });
            } else { // only radie
                $datatables->where('students.radie','=', true);
            }
        }


        return $datatables->make(true);
    }
}
