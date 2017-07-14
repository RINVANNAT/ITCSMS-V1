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
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

trait ProficencyScoreTrait
{

    public function proficencyFormScore( Request $request)
    {

        /*$ma = "((co*2)+(ce*4)+(po*10))/3";

        $co = 25;
        $ce = 15;
        $po = 5;


        $rule = '(('.$co.'*5)+('.$ce.'*5)+('.$po.'*10))/5';
        $p = eval('return '.$rule.';');

        dd($p);


        dump(preg_match('/(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)/', $ma, $matches));

        if(preg_match('/(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)/', $ma, $matches)!== FALSE){
            $operator = $matches[2];

            $position = 0;
            $operand = $matches[1];
            foreach($matches as $match) {

                $operator = $match;

                switch($operator){
                    case '+':
                        $operand = $operand + $matches[$position+1];
                        break;
                    case '-':
                        $operand =  $operand - $matches[$position+1];
                        break;
                    case '*':
                        $operand =  $operand * $matches[$position+1];
                        break;
                    case '/':
                        $operand =  $operand / $matches[$position+1];
                        break;
                }

                $position++;
            }

            dd($operand) ;
        }*/



        $renderer = [];
        $cellMaxValue = [];
        $courseAnnual = CourseAnnual::where('id', $request->course_annual_id)->first();


        $competencies = DB::table('competencies')
            ->where('competency_type_id', $courseAnnual->competency_type_id)
            ->where('is_competency', true)
            ->orderBy('name')->get();


        $additionalColumns = DB::table('competencies')
            ->where('competency_type_id', $courseAnnual->competency_type_id)
            ->where('is_competency', false)
            ->orderBy('id')->get();
        $headers = [
            [
                'Student ID',
                'Student Name',
                'Sexe',
                'Department',
                'Group',
                [ 'label' => 'Comprehension', 'colspan' => count($competencies) ],
            ],
            [
                '',
                '',
                '',
                '',
                ''
            ]
        ];

        $colWidths = [
            100,
            150,
            50,
            80,
            80,
        ];

        foreach( $competencies as $competency) {

           $properties = json_decode($competency->properties);

            $element_header = [
                'label' => $competency->name. ' | '.'('.$properties->max.'/'.$properties->max.')',
                'colspan' => 1
            ];
            $headers[1][] = $element_header;
            $colWidths[] = 130;

            $element_render = [
                'index' => strtolower($competency->name),
                'min' => $properties->min,
                'max' => $properties->max,
                'color' => $properties->color,
                'readOnly' => $properties->readOnly

            ];

            $renderer[] = $element_render;
            $cellMaxValue[strtolower($competency->name)]  =  $properties->max;
        }


        foreach ($additionalColumns as $column) {
            $headers[0][] =  $column->name;
            $headers[1][] = '';
            $colWidths[] =  130;
        }

        $comp = collect($competencies)->map(function($item) {
            return strtolower($item->name);
        })->toArray();

        return view('backend.course.courseAnnual.formScore.form_proficency_score', [
            'courseAnnual' => $courseAnnual,
            'headers' => $headers,
            'competencies'=> $comp,
            'colWidths' => $colWidths,
            'renderer' => $renderer,
            'cellMaxValue' => $cellMaxValue
        ]);
    }


    public function competencyHeader(Request $request)
    {
        $courseAnnual = CourseAnnual::where('id', $request->course_annual_id)->first();
        $competencies = DB::table('competencies')->where('competency_type_id', $courseAnnual->competency_type_id)->orderBy('name')->get();
        $additionalColumns = DB::table('additional_columns')->where('competency_type_id', $courseAnnual->competency_type_id)->get();
        $headers = [

                [
                    'Student ID',
                    'Student Name',
                    'Sexe',
                    'Department',
                    'Group',
                    [ 'label' => 'Comprehension', 'colspan' => count($competencies) ],
                ],
                [
                    '',
                    '',
                    '',
                    '',
                    ''
                ]
        ];

        foreach( $competencies as $competency) {
            $element_header = [
                'label' => $competency->name. ' | '.'('.$competency->max.'/'.$competency->max.')',
                'colspan' => 1
            ];
            $headers[1][] = $element_header;
        }
        foreach ($additionalColumns as $column) {
            $headers[0][] =  $column->column_name;
            $headers[1][] = '';
        }

        return Response::json($headers);

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
        $competencies = DB::table('competencies')
            ->where('competency_type_id', $courseAnnual->competency_type_id)
            ->where('is_competency', true)
            ->orderBy('name')->get();

        $additionalColumns = DB::table('competencies')
            ->where('competency_type_id', $courseAnnual->competency_type_id)
            ->where('is_competency', false)
            ->orderBy('id')->get();

        $studentData = $this->studentData($courseAnnual);
        $groups = collect(DB::table('groups')->get())->keyBy('id')->toArray();
        $studentGroups = $studentData['student_group'];
        $departments = Department::all()->keyBy('id')->toArray();
        $index = 0;
        $array_data = [];

        collect($studentData['student_data'])->filter(function ($item) use(&$index, &$array_data, $departments, $studentGroups, $groups, $courseAnnual, $competencies, $additionalColumns) {

            $index++;
            $groupCode = $groups[$studentGroups[$item->student_annual_id]->group_id]->code;
            $degreeCode = (($item->degree_id == config('access.degrees.degree_engineer'))?'I':'T');
            $element = [
                'student_id_card' => $item->id_card,
                'student_name' => $item->name_kh,
                'sexe' =>  $item->code,
                'department_code' => $departments[$item->department_id]['code'],
                'group_code'=> $degreeCode.$item->grade_id.'-'.$groupCode,
            ];

            foreach ($competencies as $competency) {

                $properties = json_decode($competency->properties);
                $element[strtolower($competency->name)] = $this->floatFormat(0);
            }
            foreach($additionalColumns as $column) {
                $element[$column->name] = $this->floatFormat(99.99);
            }
            $element['student_annual_id'] = $item->student_annual_id;

            $array_data[] = $element;
        });
        return  \Illuminate\Support\Facades\Response::json($array_data);

    }


}