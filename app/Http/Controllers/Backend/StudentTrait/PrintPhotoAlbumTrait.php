<?php
/**
 * Created by PhpStorm.
 * User: imac-07
 * Date: 5/24/19
 * Time: 9:11 AM
 */

namespace App\Http\Controllers\Backend\StudentTrait;


use App\Http\Requests\Backend\Student\PrintStudentIDCardRequest;
use App\Models\StudentAnnual;
use App\Models\Configuration;
use Barryvdh\Snappy\Facades\SnappyPdf;

trait PrintPhotoAlbumTrait
{
    public function request_print_album_photo(PrintStudentIDCardRequest $request){

        $studentAnnuals = StudentAnnual::select([
            'students.id_card',
            'students.name_kh',
            'students.name_latin',
            'departments.code as department',
            'departmentOptions.code as department_option',
            'students.photo',
            'studentAnnuals.department_id',
            'studentAnnuals.degree_id',
            'studentAnnuals.grade_id',
            'studentAnnuals.academic_year_id',
            'studentAnnuals.id',
            'studentAnnuals.promotion_id',
            'genders.name_en as gender',
            'degrees.name_kh as degree',
            'degrees.code as degree_code',
            'students.phone as phone'
        ])
            ->leftJoin('students','students.id','=','studentAnnuals.student_id')
            ->leftJoin('genders', 'students.gender_id', '=', 'genders.id')
            ->leftJoin('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->leftJoin('departmentOptions', 'studentAnnuals.department_option_id', '=', 'departmentOptions.id')
            ->leftJoin('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id');
        if ($group = $request->get('group')) {
            $studentAnnuals = $studentAnnuals->leftJoin('group_student_annuals', 'group_student_annuals.student_annual_id', '=', 'studentAnnuals.id')
                ->leftJoin('groups', 'group_student_annuals.group_id', '=', 'groups.id')
                ->where('group_student_annuals.semester_id','=',$request->get('semester'))
                ->whereNull('group_student_annuals.department_id');
        }
        if ($academic_year = $request->get('academic_year')) {
            $studentAnnuals = $studentAnnuals->where('studentAnnuals.academic_year_id', '=', $academic_year);
        }
        if ($degree = $request->get('degree')) {
            $studentAnnuals = $studentAnnuals->where('studentAnnuals.degree_id', '=', $degree);
        }
        if ($grade = $request->get('grade')) {
            $studentAnnuals = $studentAnnuals->where('studentAnnuals.grade_id', '=', $grade);
        }
        if ($department = $request->get('department')) {
            $studentAnnuals = $studentAnnuals->where('studentAnnuals.department_id', '=', $department);
        }
        if ($gender = $request->get('gender')) {
            $studentAnnuals = $studentAnnuals->where('students.gender_id', '=', $gender);
        }
        if ($option = $request->get('option')) {
            $studentAnnuals = $studentAnnuals->where('studentAnnuals.department_option_id', '=', $option);
        }
        if ($origin = $request->get('origin')) {
            $studentAnnuals = $studentAnnuals->where('students.origin_id', '=', $origin);
        }
        if ($group = $request->get('group')) {
            $studentAnnuals = $studentAnnuals->where('groups.code', '=', $group);
        }
        if ($search = $request->get('search')) {
            $studentAnnuals = $studentAnnuals->where(function($q) use ($search){
                $q->where('students.id_card', 'ilike', '%'.$search.'%')
                    ->orWhere('students.name_kh','ilike', '%'.$search.'%')
                    ->orWhere('students.name_latin','ilike', '%'.$search.'%');
            });
        }

        $smis_server = Configuration::where("key","smis_server")->first();
        $studentAnnuals_front = $studentAnnuals->orderBy('students.name_latin','ASC')->get();

//        return view('backend.studentAnnual.print.request_print_photo_album',compact('smis_server','studentAnnuals_front', 'group'));
        return SnappyPdf::loadView('backend.studentAnnual.print.request_print_photo_album',
            compact(
                'smis_server',
                'studentAnnuals_front',
                'group'
            )
        )
            ->setOption('encoding', 'utf-8')
            ->setPaper('a4')
            ->setOrientation('landscape')
            ->stream();
    }

}