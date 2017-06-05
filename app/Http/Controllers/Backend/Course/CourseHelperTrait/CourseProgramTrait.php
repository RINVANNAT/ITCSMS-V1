<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 6/5/17
 * Time: 11:28 AM
 */

namespace App\Http\Controllers\Backend\Course\CourseHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;



trait CourseProgramTrait
{

    public function exportList(Request $request)
    {

        $arrayData = [];

        $department = DB::table('departments')->where('id', $request->department_id)->first();
        $degree = DB::table('degrees')->where('id', $request->degree_id)->first();
        $grade = DB::table('grades')->where('id', $request->grade_id)->first();

        $coursePrograms = DB::table('courses')
            ->where([
                ['department_id', $request->department_id],
                ['degree_id', $request->degree_id],
                ['grade_id', $request->grade_id]
            ]);

        if(isset($request->semester_id)) {
            $coursePrograms = $coursePrograms->where('semester_id', $request->semester_id);
        }
        if(isset($request->department_option_id)) {
            $coursePrograms = $coursePrograms->where('department_option_id', $request->department_option_id);
            $departmentOption = DB::table('departmentOptions')->where('id', $request->department_option_id)->first();
        }

        $coursePrograms = $coursePrograms->get();

        foreach ($coursePrograms as $program) {
            $element = [
                'Name Khmer' => $program->name_kh,
                'Name in French' => $program->name_fr,
                'Name in English' => $program->name_en,
                'Code' => $program->code,
                'Class' => $degree->code.$grade->code.$department->code.(isset($departmentOption)?$departmentOption->code:''),
                'Semester' => 'semester '.$program->semester_id,
                'Time Course' => $program->time_course,
                'Time TD' => $program->time_td,
                'Time_TP' => $program->time_tp,
                'Credit' => $program->credit
            ];

            $arrayData[] = $element;
        }

        $title = 'List Course Program';
        $colHeaders = [
            'Name Khmer', 'Name French', 'Name English', 'Code', 'Class', 'Semester', 'Time Course', 'Time TD', 'Time TP', 'Creadit'
        ];

        Excel::create($title, function ($excel) use ($arrayData, $title, $colHeaders) {

            $excel->sheet($title, function ($sheet) use ($arrayData, $title, $colHeaders) {
                $sheet->fromArray($arrayData);
            });

        })->download('xls');

    }

}