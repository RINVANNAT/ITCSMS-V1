<?php
/**
 * Created by PhpStorm.
 * User: vannat
 * Date: 7/11/17
 * Time: 11:15 AM
 */

namespace App\Http\Controllers\Backend\Course\CourseHelperTrait;


use App\Models\CompetencyScore;
use App\Models\CourseAnnual;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Repositories\Backend\CompetencyScore\EloquentCompetencyScoreRepository;
use Maatwebsite\Excel\Facades\Excel;

trait ProficencyScoreTrait
{

    protected  $competencyScores;

    public function instance()
    {
        $competencyScoreRepository = new EloquentCompetencyScoreRepository();
        return $this->competencyScores = $competencyScoreRepository;
    }

    public function proficencyFormScore( Request $request)
    {

        $renderer = [];
        $cellMaxValue = [];
        $courseAnnual = CourseAnnual::where('id', $request->course_annual_id)->first();

        $competencies = DB::table('competencies')
            ->where('competency_type_id', $courseAnnual->competency_type_id)
            ->where('type', "value")
            ->orderBy('name')->get();


        $additionalColumns = DB::table('competencies')
            ->where('competency_type_id', $courseAnnual->competency_type_id)
            ->whereNotIn('type', ["value"])
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
                'readOnly' => $properties->readOnly,
                'id' => $competency->id

            ];

            $renderer[] = $element_render;
            $cellMaxValue[strtolower($competency->name)]  =  $properties->max;
        }


        $arrayAdditionalCols = [];
        foreach ($additionalColumns as $column) {

            $additionalProperties = json_decode($column->properties);
            $arrayAdditionalCols[] = [
                'index' => $column->name,
                'min' => $additionalProperties->min,
                'max' => $additionalProperties->max,
                'color' => $additionalProperties->color,
                'readOnly' => $additionalProperties->readOnly,
                'id' => $column->id

            ];
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
            'cellMaxValue' => $cellMaxValue,
            'additionalCols' => $arrayAdditionalCols
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
        $array_data = $this->getCompetencyData($courseAnnual);
        return  \Illuminate\Support\Facades\Response::json($array_data);

    }

    private function getCompetencyData($courseAnnual)
    {
        $index = 0;
        $array_data = [];
        $competencyScoreKeyByIds = [];

        //$courseAnnual = CourseAnnual::where('id', $request->course_annual_id)->first();

        $competencies = DB::table('competencies')
            ->where('competency_type_id', $courseAnnual->competency_type_id)
            ->where('type', "value")
            ->orderBy('name')->get();

        $additionalColumns = DB::table('competencies')
            ->where('competency_type_id', $courseAnnual->competency_type_id)
            ->whereNotIn('type', ["value"])
            ->orderBy('id')->get();

        $studentData = $this->studentData($courseAnnual);
        $groups = collect(DB::table('groups')->get())->keyBy('id')->toArray();
        $studentGroups = $studentData['student_group'];
        $departments = Department::all()->keyBy('id')->toArray();

        $arrayStudentAnnualIds = collect($studentData['student_data'])->pluck('student_annual_id')->toArray();

        $competencyScores = DB::table('competency_scores')->where(function ($query) use($courseAnnual, $arrayStudentAnnualIds) {
            $query->where('course_annual_id', '=', $courseAnnual->id)
                ->whereIn('student_annual_id', $arrayStudentAnnualIds);
        })->get();

        $competencyScoresCollection = collect($competencyScores);

        $competencyScoresCollection->map(function($item) use( &$competencyScoreKeyByIds){

            $competencyScoreKeyByIds[$item->student_annual_id][$item->competency_id] = $item;
        })->toArray();

        collect($studentData['student_data'])->filter(function ($item) use(&$index, &$array_data, $departments, $studentGroups, $groups, $courseAnnual, $competencies, $additionalColumns, $competencyScoreKeyByIds) {

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
            $studentCompetencyScores = isset($competencyScoreKeyByIds[$item->student_annual_id])?$competencyScoreKeyByIds[$item->student_annual_id]:null;


            /*--assign each competency score----*/
            foreach ($competencies as $competency) {

                //$properties = json_decode($competency->properties);
                $competencyScore = isset($studentCompetencyScores[$competency->id])?$studentCompetencyScores[$competency->id]:null;
                $element[strtolower($competency->name)] = isset($competencyScore)?$this->floatFormat($competencyScore->score):'0.00';
            }

            /*--end assign each competency score----*/

            /*--assign total competency score base on rule----*/

            foreach($additionalColumns as $column) {

                $notCompetencyScore = isset($studentCompetencyScores[$column->id])?$studentCompetencyScores[$column->id]:null;
                $element[$column->name] = (isset($notCompetencyScore)) ? $this->floatFormat($notCompetencyScore->score):'';
            }

            /*--assign total competency score base on rule----*/
            $element['student_annual_id'] = $item->student_annual_id;


            $array_data[] = $element;
        });

        return $array_data;
    }


    // Save 1 by 1
    public function storeCompetencyScore(Request $request)
    {
        $competencyId = $request->id;
        $courseAnnualId = $request->course_annual_id;
        $colData = $request->data;
        $studentAnnualIds = collect($colData)->pluck('student_annual_id')->toArray();
        $competencyScores = $this->instance()->getCompetencyScore($studentAnnualIds, $courseAnnualId, $competencyId);

        $input= [];

        if(count($competencyScores) > 0) {

            $count = 0;

            foreach($colData as $data) {

                $eachInput = [
                    'course_annual_id' => $courseAnnualId,
                    'student_annual_id' => $data['student_annual_id'],
                    'competency_id' => $competencyId,
                    'score'=> $data['score'],
                    'created_at' => Carbon::now(),
                    'create_uid' => auth()->id()
                ];

                if(isset($competencyScores[$data['student_annual_id']])) {

                    $scoreProp = $competencyScores[$data['student_annual_id']];
                    $update = $this->instance()->update($scoreProp->id, $eachInput);
                    if($update) {
                        $count++;
                    }

                } else {

                    $store = $this->instance()->create($eachInput);
                    if($store) {
                        $count++;
                    }
                }
            }

            if($count == count($colData)) {

                return Response::json(['status' => true, 'message' => 'Updated!']);
            }

        } else {

            foreach($colData as $data) {
                $input[] = [
                    'course_annual_id' => $courseAnnualId,
                    'student_annual_id' => $data['student_annual_id'],
                    'competency_id' => $competencyId,
                    'score'=> $data['score'],
                    'created_at' => Carbon::now(),
                    'create_uid' => auth()->id()
                ];
            }

            $store = $this->instance()->create($input);
            if($store) {
                return Response::json(['status' => true, 'message' => 'Saved!']);
            }
        }

    }

    /**
     * Calculate the result
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function calculate(Request $request)
    {
        $updation =0;
        $creation = 0;
        $countRule = 0;
        $competencyScoresKeyByIds = [];
        $courseAnnualId = $request->course_annual_id;
        $studentAnnualIds = $request->student_annual_id;
        $studentAnnualIds = collect($studentAnnualIds)->filter(function($item) {

            if($item != '' && $item !=null) {
                return $item;
            }
        })->toArray();

        $courseAnnual = CourseAnnual::find($courseAnnualId)->toArray();

        $competencies = DB::table('competencies')
            ->where(function ($query) use($courseAnnual) {
                $query->whereIn('competency_type_id', DB::table('competency_types')->where('id', '=', $courseAnnual['competency_type_id'])->lists('id'));
            })
            ->get();

        $competencyName = [];
        $notCompetencyName = [];

        // to get competency name and not competency
        collect($competencies)

            ->filter(function($item) use(&$competencyName, &$notCompetencyName) {
                if($item->type == "value") {
                    $competencyName[strtolower($item->name)] = $item->id;
                    return $item;
                } else {
                    $notCompetencyName[] = $item;
                }
            })->pluck('id')->toArray();


        $competencyScores = DB::table('competency_scores')
            ->where('course_annual_id', '=', $courseAnnualId)
            ->whereIn('student_annual_id', array_values($studentAnnualIds))
           /* ->whereIn('competency_id', $competencyIds)*/
            ->get();


        collect($competencyScores)->filter(function ($item) use(&$competencyScoresKeyByIds){
            $competencyScoresKeyByIds[$item->student_annual_id][$item->competency_id] = $item;
        })->toArray();

        foreach($notCompetencyName as $competency) {

            $arrayInput = [];

            if($competency->calculation_rule != null) {
                $countRule++;

                /*---loop all student to store data ---*/

                foreach($competencyScoresKeyByIds as $studentAnnualId => $scores) {

                    $constructNewRule = $this->getExpression($competency->calculation_rule, $competencyName, $scores);
                    $score =  eval('return '.$constructNewRule.';');


                    if($competency->condition_rule != null) {

                        $baseLevelType = json_decode($competency->condition_rule);
                        $new_score = null;
                        foreach($baseLevelType as $levelType) {

                            if($score > $levelType->min && $score < $levelType->max) {
                                $new_score = $levelType->value;
                                break;
                            }
                        }

                        $input = [
                            'course_annual_id' => $courseAnnualId,
                            'student_annual_id' => $studentAnnualId,
                            'competency_id' => $competency->id,
                            'score'=> $new_score,
                            'created_at' => Carbon::now(),
                            'create_uid' => auth()->id()
                        ];

                    } else {

                        $input = [
                            'course_annual_id' => $courseAnnualId,
                            'student_annual_id' => $studentAnnualId,
                            'competency_id' => $competency->id,
                            'score'=> $this->floatFormat($score),
                            'created_at' => Carbon::now(),
                            'create_uid' => auth()->id()
                        ];
                    }
                    if(isset($scores[$competency->id])) {
                        /*--- update record -- */

                        $update = $this->instance()->update($scores[$competency->id]->id, $input);
                        if($update) {
                            $updation++;
                        }

                    } else {
                        /*--- create new record for array input ---*/
                        $arrayInput[] = $input;
                        $creation++;
                    }
                }
                /*----end of loop student ----*/


                /*---insert into database*/

                if($creation > 0) {
                    $this->instance()->create($arrayInput);
                }
            }
        }

        if((($updation + $creation)/$countRule) == count($competencyScoresKeyByIds)) {

            return Response::json(['status' => true, 'message' => 'Data Calculated!']);
        }

    }


    // replace competency with real score value
    private function getExpression($originalRule, $competencies, $scores)
    {

        foreach($competencies as $key => $id) {

            if(isset($scores[$id])) {
                $originalRule = str_replace(strtolower($key),$scores[$id]->score, $originalRule);
            } else {
                // Some score is empty, replace with 0
                $originalRule = str_replace(strtolower($key),0, $originalRule);
            }

        }

        return $originalRule;
    }


    public function export(Request $request, $courseAnnualId)
    {

        $courseAnnual = CourseAnnual::find($courseAnnualId);
        $data = $this->getCompetencyData($courseAnnual);
        Excel::create($courseAnnual->name_en, function ($excel) use ($data, $courseAnnual) {

            $excel->sheet($courseAnnual->name_en, function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });

        })->download('xls');
    }

    public function importCompetencyScore(Request $request, $courseAnnualId)
    {

        $updatation = 0;
        $creation = 0;
        $checkIfStringExist = 0;
        $isScoreUpperThanMax = 0;

        $arrayDataUploaded = [];
        $competencyScoresKeyByIds = [];

        $courseAnnual = CourseAnnual::find($courseAnnualId);
        $competencies = DB::table('competencies')
            ->where([
                ['competency_type_id', '=', $courseAnnual->competency_type_id],
                ['type', '=', "value"]
            ])->get();
        $competencyIds = collect($competencies)->pluck('id')->toArray();

        if ($request->file('import') != null) {

            $import = "score" . '_' . Carbon::now()->getTimestamp() . '.' . $request->file('import')->getClientOriginalExtension();
            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/course_annuals/', $import
            );
            $storage_path = base_path() . '/public/assets/uploaded_file/course_annuals/' . $import;
        } else {
            return redirect()->back()->with(['status' => false, 'message' => 'No File Choosen!']);
        }

        if($this->isValidFile($storage_path, $competencies)) {
            return redirect()->back()->with(['status' => false, 'message'=> 'Please check your inputed file! It maybe wrong.']);
        }

        Excel::load($storage_path, function($results) use(&$arrayDataUploaded) {
            $arrayDataUploaded = $results->all()->toArray();
        });

        $competencyScores = DB::table('competency_scores')
            ->where('course_annual_id', '=', $courseAnnualId)
            ->whereIn('competency_id', $competencyIds)
            ->get();

        collect($competencyScores)->filter(function ($item) use(&$competencyScoresKeyByIds){
            $competencyScoresKeyByIds[$item->student_annual_id][$item->competency_id] = $item;
        })->toArray();

        $arrayInput = [];
        foreach($arrayDataUploaded as $studentData ) {

            collect($competencies)->map(function($item) use($studentData, &$arrayInput, $competencyScoresKeyByIds, $courseAnnual, &$creation, &$updatation, &$checkIfStringExist, &$isScoreUpperThanMax) {

                $properties = json_decode($item->properties);

                if(isset($competencyScoresKeyByIds[(int)$studentData['student_annual_id']])) {

                    $competencyScore = $competencyScoresKeyByIds[(int)$studentData['student_annual_id']];

                    if(isset($competencyScore[$item->id])) {

                        if($studentData[strtolower($item->name)] == null || is_numeric($studentData[strtolower($item->name)])) {

                            if( $studentData[strtolower($item->name)] <= $properties->max) {

                                /*---update record -----*/
                                $input = [
                                    'course_annual_id' => $courseAnnual->id,
                                    'student_annual_id' => (int)$studentData['student_annual_id'],
                                    'competency_id' => $item->id,
                                    'score'=> $this->floatFormat(is_numeric($studentData[strtolower($item->name)])?$studentData[strtolower($item->name)]:0),
                                    'updated_at' => Carbon::now(),
                                    'write_uid' => auth()->id()
                                ];

                                $updatation++;
                                $score = $competencyScore[$item->id];
                                $this->instance()->update($score->id, $input);

                            } else {
                                $isScoreUpperThanMax++;
                            }

                        } else {

                            $checkIfStringExist++;

                        }
                    } else {

                        if($studentData[strtolower($item->name)] == null || is_numeric($studentData[strtolower($item->name)])) {
                            if($studentData[strtolower($item->name)] <= $properties->max) {

                                $input = [
                                    'course_annual_id' => $courseAnnual->id,
                                    'student_annual_id' => (int)$studentData['student_annual_id'],
                                    'competency_id' => $item->id,
                                    'score'=> $this->floatFormat(is_numeric($studentData[strtolower($item->name)])?$studentData[strtolower($item->name)]:0),
                                    'created_at' => Carbon::now(),
                                    'create_uid' => auth()->id()
                                ];
                                /* --create record --*/

                                $creation++;
                                $arrayInput[] = $input;
                            } else{

                                $isScoreUpperThanMax++;
                            }
                        } else {
                            $checkIfStringExist++;
                        }
                    }
                } else {

                    /*---create record ----*/

                    if($studentData[strtolower($item->name)] == null || is_numeric($studentData[strtolower($item->name)])) {
                        if($studentData[strtolower($item->name)] <= $properties->max) {

                            $input = [
                                'course_annual_id' => $courseAnnual->id,
                                'student_annual_id' => (int)$studentData['student_annual_id'],
                                'competency_id' => $item->id,
                                'score'=> $this->floatFormat(is_numeric($studentData[strtolower($item->name)])?$studentData[strtolower($item->name)]:0),
                                'created_at' => Carbon::now(),
                                'create_uid' => auth()->id()
                            ];

                            $creation++;
                            $arrayInput[] = $input;
                        } else{

                            $isScoreUpperThanMax++;

                        }
                    } else {

                        $checkIfStringExist++;
                    }
                }

            });
        }

        if($checkIfStringExist > 0) {
            return redirect()->back()->with(['status' => false, 'message' => 'Error! Please find value in cell there maybe wrong!']);
        }
        if($isScoreUpperThanMax > 0) {
            return redirect()->back()->with(['status' => false, 'message' => 'Wrong Value In Cell File!']);
        }

        if(file_exists($storage_path)) {
            unlink($storage_path);
        }
        $this->instance()->create($arrayInput);

        if(($creation + $updatation)/count($competencies) === count($arrayDataUploaded)) {
            return redirect()->back()->with(['status' => true, 'message' => 'Score Uploaded']);
        } else {

        }

    }

    private function isValidFile($storage_path, $competencies)
    {

        $isError = false;
        Excel::load($storage_path, function($reader) use(&$isError, $competencies) {

            $check = 0;
            $firstrow = $reader->first()->toArray();

            foreach($competencies as $competency) {
                if(isset($firstrow[ strtolower($competency->name) ])) {
                    $check++;
                }
            }
            if ((!isset($firstrow['student_id_card'])) || (!isset($firstrow['student_name'])) ) {
                if($check != count($competencies)) {
                    $isError = true;
                }
            }
        });

        if($isError) {
            return true;
        }
        return false;
    }


    public function requestPrintCertificate(Request $request)
    {
        $course_annual_id = $request->get("course_annual_id");
        $course_annual = CourseAnnual::find($course_annual_id);
        $students = $this->studentData($course_annual)["student_data"];

        $departments = Department::lists("code","id")->toArray();
        $degrees = Degree::lists("code","id")->toArray();
        return view('backend.vocational_student.print.request_print_certificate_fr',compact("students","departments","degrees"));
    }
    public function printCertificate(Request $request)
    {
        return view('backend.course.courseAnnual.formScore.prints.certificate');
    }


}