<?php

namespace App\Http\Controllers\Backend\StudentTrait;
use App\Http\Requests\Backend\Student\GenerateStudentGroupRequest;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Enum\SemesterEnum;
use App\Models\Group;
use App\Models\StudentAnnual;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Degree;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Support\Facades\Response;
use App\Models\Enum\ScoreEnum;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 6/21/17
 * Time: 5:34 PM
 */
trait StudentAnnualTrait
{


    public function formGenerateGroup(GenerateStudentGroupRequest $request) {

        $degrees = Degree::lists('name_en','id');
        $grades = Grade::lists('name_en', 'id');
        $departments = Department::where('parent_id', 11)->orderBy('id')->get();//lists('code', 'id');
        $academicYears = AcademicYear::orderBy('id', 'DESC')->lists('name_latin', 'id');
        $semesters = Semester::lists('name_en', 'id');
        $options = DepartmentOption::get();

        return view('backend.studentAnnual.includes.form_generate_student_group', compact('academicYears', 'departments', 'grades', 'degrees', 'semesters', 'options'));
    }

    public function loadCourse(Request $request)
    {
        $coursePrograms = DB::table('courses')
            ->where([
                ['degree_id', $request->degree_id],
                ['grade_id', $request->grade_id],
                ['semester_id', $request->semester_id],

            ]);

        if($request->department_id) {
            $department = Department::where('id', $request->department_id)->first();
            $coursePrograms = $coursePrograms->where('department_id', $request->department_id);
        }
        if($request->department_option_id) {
            $coursePrograms = $coursePrograms->where('department_option_id', $request->department_option_id);
        }

        $coursePrograms = $coursePrograms->get();

        $element = [
            'text'=>  isset($department)?$department->code:'All Courses',
        ];
        if(count($coursePrograms) > 0) {
            foreach($coursePrograms as $course) {

                $childrens = [
                    'id'=> $course->id,
                    'text'=> (($course->degree_id == ScoreEnum::Degree_I)?'I':'T').$course->grade_id."-S".$course->semester_id." | ".$course->name_en,
                    'selected' => '',
                    'value' => $course->id
                ];

                $element['children'][] = $childrens;

            }
        } else {
            $element['children'][] = 'No Course';
        }

        $data[] = $element;

        return Response::json(['status' => true, 'data' => $data]);

    }


    private function getStudents($department_id, $academic_year_id, $degree_id, $grade_id, $semester_id, $department_option_id)
    {

        $studentAnnuals = DB::table('studentAnnuals')
            ->join('students', 'students.id', '=', 'studentAnnuals.student_id')
            ->where([
                ['studentAnnuals.degree_id', $degree_id],
                ['studentAnnuals.grade_id', $grade_id],
                ['studentAnnuals.academic_year_id', $academic_year_id]
            ]);

        if($department_option_id != null && $department_id != '') {
            $studentAnnuals = $studentAnnuals->where('studentAnnuals.department_option_id', $department_option_id);
        }

        if($department_id) {
            $department = Department::where('id', $department_id)->first();

            if($department->is_vocational) {

                if($semester_id > SemesterEnum::SEMESTER_ONE) {

                    $studentAnnuals = $studentAnnuals->where(function($query) {
                        $query->whereNull('students.radie')
                            ->orWhere('students.radie', '=', false);
                    });
                }
                $studentAnnuals = $studentAnnuals
                    ->select('studentAnnuals.*', 'students.id_card', 'students.name_latin','students.radie')
                    ->orderBy('students.name_latin')->get();
            } else {

                if($semester_id > SemesterEnum::SEMESTER_ONE) {

                    $studentAnnuals = $studentAnnuals->where(function($query) {
                        $query->whereNull('students.radie')
                            ->orWhere('students.radie', '=', false);
                    });
                }
                $studentAnnuals = $studentAnnuals->where('studentAnnuals.department_id', $department->id)
                    ->select('studentAnnuals.*', 'students.id_card', 'students.name_latin','students.radie')
                    ->orderBy('students.name_latin')->get();
            }
        } else {

            if($semester_id > SemesterEnum::SEMESTER_ONE) {

                $studentAnnuals = $studentAnnuals->where(function($query) {
                    $query->whereNull('students.radie')
                        ->orWhere('students.radie', '=', false);
                });
            }
            $studentAnnuals = $studentAnnuals
                ->select('studentAnnuals.*', 'students.id_card', 'students.name_latin','students.radie')
                ->orderBy('students.name_latin')->get();
        }

        return $studentAnnuals;

    }

    public function getNumberStudent(Request $request)
    {

        $department_id = $request->department_id;
        $academic_year_id = $request->academic_year_id;
        $degree_id = $request->degree_id;
        $grade_id = $request->grade_id;
        $semester_id = $request->semester_id;
        $department_option_id = $request->department_option_id;

        $studentAnnuals = $this->getStudents($department_id, $academic_year_id, $degree_id, $grade_id, $semester_id, $department_option_id);
        return \Illuminate\Support\Facades\Response::json(['status' => true, 'count' => count($studentAnnuals)]);

    }

    public function generatGroup(Request $request) {
        $dataRequest = $request->all();
        return Response::json(['status'=>true, 'request' =>$dataRequest, 'message' => 'Generating !!!', 'type'=>'info' ]);
    }

    public function generate_group(GenerateStudentGroupRequest $request){

        $studentListByGroup = [];
        $numberStudentPerGroup = $request->number_student;
        $department_id = $request->department_id;
        $degree_id = $request->degree_id;
        $grade_id = $request->grade_id;
        $academic_year_id = $request->academic_year_id;
        $semester_id = $request->semester_id;
        $department_option_id = $request->department_option_id;
        $rule = $request->rule;
        $prefix = $request->prefix;

        if($department_id) {
            $department = Department::where('id', $department_id)->first();
        } else {
            $department = null;
        }

        $groups = Group::all()->keyBy('code')->toArray();

        $studentAnnual =  $this->getStudents($department_id, $academic_year_id, $degree_id, $grade_id, $semester_id, $department_option_id);

        $dataToDownload = [];

        if($rule == 'by_name') {
            usort($studentAnnual, function($a, $b) {
                return strcmp(strtoupper($a->name_latin), strtoupper($b->name_latin));
            });
        } else {
            usort($studentAnnual, function($a, $b) {
                return strcmp(strtoupper($a->id_card), strtoupper($b->id_card));
            });

        }
        $allStudents = count($studentAnnual);

        if($request->suffix == 'number') {
            $key=1;
        } else {
            $key = 'A';
        }

        $remainder = $allStudents % $numberStudentPerGroup;

        $numGroup = (int)($allStudents / $numberStudentPerGroup);// number of group
        $afterAddedRemainder = $remainder - (int)round($numGroup / 2); //after added the remain student into an odd group but still remain student

        $index = 0;
        $check =0;

        $isGenerated = $this->isGerneratedGroup($studentAnnual, $request, $numGroup, $key, $department);

        if($isGenerated) {

            return redirect()->back()->with([
                'status' => false,
                'message' => 'With your selected option, Group students are already generated!!'
            ]);
        }

        foreach ($studentAnnual as $student) {


            $code = /*$prefix.'-'.*/$key;

            /* --store or update group student annual----*/

            $groups = $this->storeOrUpdateGroup($groups, $student,$academic_year_id,$code, $semester_id, $department, $studentListByGroup);
            /*--end store or update ----*/
            $index++;
            $studentListByGroup[$code][] = $student;//($rule == 'by_name')? strtoupper($student->name_latin):strtoupper($student->id_card);

            if ($index == $numberStudentPerGroup) {

                if($remainder > $numGroup) {
                    $numberStudentPerGroup++;
                    $remainder = $allStudents % $numberStudentPerGroup;
                    $afterAddedRemainder = $remainder - (int)round($numGroup / 2);

                } else {

                    if ($remainder > 0) {

                        //add the remain student to the odd group first

                        if (count($studentListByGroup) % 2 != 0) {// check if the student in the group is paire or odd
                            $remainder--;

                        } else {

                            //if the remain student added to the odd group of student but still remaining some student so we have to add the student to the paire group

                            if($afterAddedRemainder <= 0) {
                                $key++;
                                $index = 0;
                            } else {
                                $afterAddedRemainder--;
                            }
                        }

                    } else {
                        $key++;
                        $index = 0;
                    }
                }
            } elseif ($index > $numberStudentPerGroup) {
                $key++;
                $index = 0;
            }
        }

        foreach($studentListByGroup as $group => $students)  {

           $studentGroup = collect($students)->map(function($item)use($department_id, $department_option_id, $group, $semester_id) {

               $element = [
                   'ID-Card' => $item->id_card,
                   'Student Name' => strtoupper($item->name_latin),
                   'Year' => $item->academic_year_id,
               ];

               if($department_id) {
                   $department = Department::where('id', $department_id)->first();
                   $element += ['Department-Code' => $department->code];

                   if($department_option_id) {
                       $option = DepartmentOption::where('id', $department_option_id)->first();
                       $element += ['Option' => $option->code];
                   }
               }
               if($semester_id) {
                   $element += ['Semester' => $semester_id];
               }
               $element +=['Group' => $group];
               return $element;

           })->toArray();

            $dataToDownload = array_merge($dataToDownload, $studentGroup);
        }

        Excel::create('Generated-Group', function ($excel) use ($dataToDownload) {

            $excel->sheet('Generated-Group', function ($sheet) use ($dataToDownload) {
                $sheet->fromArray($dataToDownload);
            });

        })->download('xls');

    }

    private function storeOrUpdateGroup($groups, $student, $academicYearId, $groupCode, $semesterId, $department, $studentListByGroup)
    {

        if(isset($groups[$groupCode])) {
            /*--store only group_student_annual---*/

            $groupStudentAnnualInput = [
                'department_id' => isset($department)?(($department->is_vocational)? $department->id:null):null,
                'semester_id' => $semesterId,
                'student_annual_id' => $student->id,
                'group_id' => $groups[$groupCode]['id'],
                'created_at' => Carbon::now()
            ];

            $this->groups->storeGroupStudentAnnual($groupStudentAnnualInput);

        } else {
            /*---create new record for groups table and store in a groups student annual table--*/


            if(!isset($studentListByGroup[$groupCode])) {

                $newGroupInput = [
                    'code' => $groupCode,
                    'created_at' => Carbon::now()
                ];
                $newGroup = $this->groups->create($newGroupInput);

                /*---append new group to the group param ---*/
                $groups[$newGroup->code] = collect($newGroup)->toArray();

                $groupStudentAnnualInput = [
                    'department_id' => isset($department)?(($department->is_vocational)? $department->id:null):null,
                    'semester_id' => $semesterId,
                    'student_annual_id' => $student->id,
                    'group_id' => $newGroup->id,
                    'created_at' => Carbon::now()
                ];
                $this->groups->storeGroupStudentAnnual($groupStudentAnnualInput);
            }
        }

        return $groups;

    }

    function isGerneratedGroup($students,$request, $numGroup, $key, $department)
    {

        $requestGroupCodes = [];
        for($int = 0; $int < $numGroup ; $int++) {
            $requestGroupCodes[] = ($key++);
        }


        $groups = Group::whereIn('code', $requestGroupCodes)->get();


        if(count($groups) == 0) {
            /*--allow generating group--*/
            return false;
        } else {

            if(count($groups) >= $numGroup) {

                $groupIds = collect($groups)->pluck('id')->toArray();
                $studentAnnualIds = collect($students)->pluck('id')->toArray();
                $groupStudentAnnuals = $this->groupStudentAnnual($groupIds, $studentAnnualIds, $request->semester_id, $department);

                if(count($groupStudentAnnuals) >= count($students)) {
                    return true;
                } else {
                    return false;
                }

            } else {

                return false;
            }
        }
    }

    function groupStudentAnnual($groupIds, $studentAnnualIds, $semesterId, $department)
    {

        if (isset($department)) {
            if ($department->is_vocational) {

                $groupStudentAnnuals = DB::table('group_student_annuals')
                    ->whereIn('student_annual_id', $studentAnnualIds)
                    ->where('semester_id', $semesterId)
                    ->where('department_id', $department->id)
                    ->whereIn('group_id', $groupIds)->get();

            } else {

                $groupStudentAnnuals = DB::table('group_student_annuals')
                    ->whereIn('student_annual_id', $studentAnnualIds)
                    ->where('semester_id', $semesterId)
                    ->whereNull('department_id')
                    ->whereIn('group_id', $groupIds)->get();

            }
        } else {
            $groupStudentAnnuals = DB::table('group_student_annuals')
                ->whereIn('student_annual_id', $studentAnnualIds)
                ->where('semester_id', $semesterId)
                ->whereNull('department_id')
                ->whereIn('group_id', $groupIds)->get();
        }


        return $groupStudentAnnuals;

    }


    public function search(Request $request)
    {

        $page = $request->page;
        $resultCount = 25;
        $offset = ($page - 1) * $resultCount;

        $students = DB::table('students')
            ->where('students.id_card', 'ilike', "%".$request->term . "%")
            ->select([
                'students.id as student_id',
                'students.id_card',
                'students.name_kh as text',
                'students.name_latin',
                'students.photo',
                'genders.code as gender',
                //'departments.code as department'
            ])
            //->leftJoin('departments','departments.id','=','studentAnnuals.department_id')
            ->leftJoin('genders','genders.id','=','students.gender_id');

        $client = $students
            ->orderBy('name_latin')
            ->skip($offset)
            ->take($resultCount)
            ->get();

        $studentIds = collect($client)->pluck('student_id')->toArray();

        $studentAnnuals = StudentAnnual::join('departments', function($query) use($studentIds) {
            $query->on('departments.id', '=', 'studentAnnuals.department_id')
                ->where('academic_year_id','=', AcademicYear::max('id'))
                ->whereIn('studentAnnuals.student_id', $studentIds);
            })
            ->select(
                'departments.code as department',
                'studentAnnuals.degree_id',
                'studentAnnuals.grade_id',
                'studentAnnuals.student_id as student_id_',
                'studentAnnuals.id as id'
            )->get();

        $studentAnnuals = collect($studentAnnuals)->keyBy('student_id_')->toArray();

        $arrayData= [];
        collect($client)->filter(function($item) use($studentAnnuals, &$arrayData) {
            if(isset($studentAnnuals[$item->student_id])) {
                $item = (object) array_merge((array)$item, $studentAnnuals[$item->student_id]);
                $arrayData[]= $item;
                return $arrayData;
            }
        })->toArray();


        $count = Count($students->get());
        $resultCount = count($arrayData);
        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;

        $results = array(
            'results' => $arrayData,
            'pagination' => array(
                "more" => $morePages
            )
        );
        return response()->json($results);

    }

}