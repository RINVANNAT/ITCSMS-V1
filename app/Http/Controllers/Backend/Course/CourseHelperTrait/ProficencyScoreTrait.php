<?php
/**
 * Created by PhpStorm.
 * User: vannat
 * Date: 7/11/17
 * Time: 11:15 AM
 */

namespace App\Http\Controllers\Backend\Course\CourseHelperTrait;


use App\Models\CourseAnnual;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

trait ProficencyScoreTrait
{


    public function proficencyFormScore( Request $request)
    {
        $courseAnnual = CourseAnnual::where('id', $request->course_annual_id)->first();
        return view('backend.course.courseAnnual.formScore.form_proficency_score', ['courseAnnual' => $courseAnnual]);
    }



    private function studentData($courseAnnual)
    {

        $groups = DB::table('course_annual_classes')
            ->where('course_annual_id', '=', $courseAnnual->id)
            ->whereNull('course_session_id')
            ->lists('group_id');

        $studentByCourse = $this->getStudentByDeptIdGradeIdDegreeId([$courseAnnual->department_id], [$courseAnnual->degree_id], [$courseAnnual->grade_id], $courseAnnual->academic_year_id);

        if(count($groups)) {

            $studentAnnualIds = DB::table('group_student_annuals')->whereIn('group_id', $groups)
                ->where('semester_id', $courseAnnual->semester_id);
        } else {
            $studentAnnualIds = DB::table('group_student_annuals')
                ->where('semester_id', $courseAnnual->semester_id);
        }

        $studentAnnualGroups = $studentAnnualIds->where('group_student_annuals.department_id', $courseAnnual->department_id)->get();

        $studentAnnualGroupsCollection  = collect($studentAnnualGroups);
        $studentAnnualGroupsKeyByIds = $studentAnnualGroupsCollection->keyBy('student_annual_id')->toArray();
        $studentAnnualIds = $studentAnnualGroupsCollection->pluck('student_annual_id')->toArray();


        $studentByCourse = $studentByCourse->whereIn('studentAnnuals.id', $studentAnnualIds)
            ->orderBy('students.name_latin');

        if($courseAnnual->semester_id >  1) {

            $studentByCourse = $studentByCourse
                ->where(function($query) {
                    $query->where('students.radie','=',  false)
                        ->orWhereNull('students.radie');
                })
                ->orderBy('students.name_latin')->get();

        } else {
            $studentByCourse = $studentByCourse->orderBy('students.name_latin')->get();
        }


        return [
            'student_group' => $studentAnnualGroupsKeyByIds,
            'student_data' => $studentByCourse
        ];


    }

    public function proficencyData(Request $request)
    {

        $courseAnnual = CourseAnnual::where('id', $request->course_annual_id)->first();
        $studentData = $this->studentData($courseAnnual);
        $groups = collect(DB::table('groups')->get())->keyBy('id')->toArray();
        $studentGroups = $studentData['student_group'];
        $departments = Department::all()->keyBy('id')->toArray();

        $index = 0;
        $array_data = [];
        collect($studentData['student_data'])->filter(function ($item) use(&$index, &$array_data, $departments, $studentGroups, $groups) {
            $index++;
            dd($item);
            $array_data = [
                'number' => $index,
                'student_id_card' => $item->id_card,
                'student_name' => $item->name_kh,
                'sexe' =>  $item->code,
                'department_code' => $departments[$item->department_id]->code,
                'group_code'=> ($item),
                'co' => 89.99,
                'ce' => 90.90,
                'po' => 56.78,
                'pe' => 67.67,
                'total_score' => 99.99,
                'admission' => 'Admise'
            ];
        })->toArray();



        $data =  [
            [
                'number' => 1,
                'student_id_card' => 'e20120094',
                'student_name' => 'Vannat RIN',
                'sexe' =>  'M',
                'department_code' => 'GIC',
                'group_code'=> 'I3-A',
                'co' => 89.99,
                'ce' => 90.90,
                'po' => 56.78,
                'pe' => 67.67,
                'total_score' => 99.99,
                'admission' => 'Admise'
            ],
            [
                'number' => 2,
                'student_id_card' => 'e20120094',
                'student_name' => 'Vannat RIN',
                'sexe' =>  'M',
                'department_code' => 'GIC',
                'group_code'=> 'I3-A',
                'co' => 89.99,
                'ce' => 90.90,
                'po' => 56.78,
                'pe' => 67.67,
                'total_score' => 99.99,
                'admission' => 'Admise'
            ],
            [
                'number' => 3,
                'student_id_card' => 'e20120094',
                'student_name' => 'Vannat RIN',
                'sexe' =>  'M',
                'department_code' => 'GIC',
                'group_code'=> 'I3-A',
                'co' => 89.99,
                'ce' => 90.90,
                'po' => 56.78,
                'pe' => 67.67,
                'total_score' => 99.99,
                'admission' => 'Admise'
            ],
            [
                'number' => 4,
                'student_id_card' => 'e20120094',
                'student_name' => 'Vannat RIN',
                'sexe' =>  'M',
                'department_code' => 'GIC',
                'group_code'=> 'I3-A',
                'co' => 89.99,
                'ce' => 90.90,
                'po' => 56.78,
                'pe' => 67.67,
                'total_score' => 99.99,
                'admission' => 'Admise'
            ],
            [
                'number' => 5,
                'student_id_card' => 'e20120094',
                'student_name' => 'Vannat RIN',
                'sexe' =>  'M',
                'department_code' => 'GIC',
                'group_code'=> 'I3-A',
                'co' => 89.99,
                'ce' => 90.90,
                'po' => 56.78,
                'pe' => 67.67,
                'total_score' => 99.99,
                'admission' => 'Admise'
            ],
            [
                'number' => 6,
                'student_id_card' => 'e20120094',
                'student_name' => 'Vannat RIN',
                'sexe' =>  'M',
                'department_code' => 'GIC',
                'group_code'=> 'I3-A',
                'co' => 89.99,
                'ce' => 90.90,
                'po' => 56.78,
                'pe' => 67.67,
                'total_score' => 99.99,
                'admission' => 'Admise'
            ]
        ];


        return  \Illuminate\Support\Facades\Response::json($data);

    }


}