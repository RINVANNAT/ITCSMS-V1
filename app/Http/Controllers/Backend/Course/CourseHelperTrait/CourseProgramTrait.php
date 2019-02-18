<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 6/5/17
 * Time: 11:28 AM
 */

namespace App\Http\Controllers\Backend\Course\CourseHelperTrait;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


trait CourseProgramTrait
{

    public function exportList(Request $request)
    {

        $arrayData = [];
        $user = auth()->user();
        $departmentIds = [];
        if (isset($request->department_id)) {
            $department = DB::table('departments')->where('id', $request->department_id)->first();
            array_push($departmentIds, $department->id);
        } else {
            try {
                $employee = Employee::where('user_id', $user->id)->first();
                $department = Department::where('id', $employee->department_id)->first();
                array_push($departmentIds, $department->id);
            } catch (\Exception $e) {
                $deptIds = Department::where([
                    'is_specialist' => true,
                ])->pluck('id');
                $departmentIds = $deptIds;
            }
        }

        $degree = DB::table('degrees')->where('id', $request->degree_id)->first();
        $grade = DB::table('grades')->where('id', $request->grade_id)->first();

        if (count($departmentIds) > 1) {
            $header = "Course Program - All Departments";
        } else {
            $header = "Course Program - Department: " . $department->code . " , Degree: " . $degree->name_en . " ,Grade: " . $grade->code;
        }


        $coursePrograms = DB::table('courses')
            ->whereIn('department_id', $departmentIds)
            ->where([
                ['degree_id', $request->degree_id],
                ['grade_id', $request->grade_id],
                ['active', true],
            ]);

        if (isset($request->semester_id) && $request->semester_id != '') {
            $coursePrograms = $coursePrograms->where('semester_id', $request->semester_id);
            $header = $header . " , Semester: " . $request->semester_id;
        }

        if (isset($request->department_option_id) && $request->department_option_id != 'undefined' && $request->department_option_id != '') {

            $coursePrograms = $coursePrograms->where('department_option_id', $request->department_option_id);
            $departmentOption = DB::table('departmentOptions')->where('id', $request->department_option_id)->first();

            $header = $header . " ,Option: " . $departmentOption->name_en;
        }

        $coursePrograms = $coursePrograms->orderBy('department_id')
            ->orderBy('semester_id')
            ->get();

        foreach ($coursePrograms as $program) {
            $dept = Department::find($program->department_id);
            $element = [
                'ID' => $program->id,
                'Name Khmer' => $program->name_kh,
                'Name in French' => $program->name_fr,
                'Name in English' => $program->name_en,
                'Code' => $program->code,
                'Class' => $class = $degree->code . $grade->code . $dept->code . (isset($departmentOption) ? $departmentOption->code : ''),
                'Semester' => 'semester ' . $program->semester_id,
                'Time Course' => $program->time_course,
                'Time TD' => $program->time_td,
                'Time_TP' => $program->time_tp,
                'Credit' => $program->credit
            ];

            $arrayData[] = $element;
        }

        $title = 'List Course Program';

        $colHeaders = [
            'ID', 'Name Khmer', 'Name French', 'Name English', 'Code', 'Class', 'Semester', 'Time Course', 'Time TD', 'Time TP', 'Creadit'
        ];

        Excel::create($title, function ($excel) use ($arrayData, $title, $colHeaders, $header) {

            $excel->sheet($title, function ($sheet) use ($arrayData, $title, $colHeaders, $header) {

                $sheet->row(1, [$header]);
                $sheet->row(2, $colHeaders);
                foreach ($arrayData as $data) {
                    $sheet->appendRow($data);
                }
                $sheet->mergeCells('A1:K1');
                $sheet->cells('A1:K1', function ($cell) {
                    $cell->setAlignment('center');
                });
            });

        })->download('xls');

    }
}