<?php

namespace App\Http\Controllers\Backend\StudentTrait;
use App\Http\Requests\Backend\Student\GenerateStudentGroupRequest;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Enum\SemesterEnum;
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
                $studentAnnuals = $studentAnnuals->orderBy('students.name_latin')->get();
            } else {

                if($semester_id > SemesterEnum::SEMESTER_ONE) {

                    $studentAnnuals = $studentAnnuals->where(function($query) {
                        $query->whereNull('students.radie')
                            ->orWhere('students.radie', '=', false);
                    });
                }
                $studentAnnuals = $studentAnnuals->where('studentAnnuals.department_id', $department->id)->orderBy('students.name_latin')->get();
            }
        } else {

            if($semester_id > SemesterEnum::SEMESTER_ONE) {

                $studentAnnuals = $studentAnnuals->where(function($query) {
                    $query->whereNull('students.radie')
                        ->orWhere('students.radie', '=', false);
                });
            }
            $studentAnnuals = $studentAnnuals->orderBy('students.name_latin')->get();
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
        return Response::json(['status'=>true, 'request' =>$dataRequest, 'message' => 'Generating !!!' ]);
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

        foreach ($studentAnnual as $student) {

            $index++;
            $studentListByGroup[$prefix.'-'.$key][] = $student;//($rule == 'by_name')? strtoupper($student->name_latin):strtoupper($student->id_card);

            /*$update = DB::table('studentAnnuals')
                ->where('id', $student->id)
                ->update(['group' => $prefix.$key]);*/

            /*if($update) {
                $check++;
            }*/



            if ($index == $numberStudentPerGroup) {

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

            } elseif ($index > $numberStudentPerGroup) {
                $key++;
                $index = 0;
            }
        }


        foreach($studentListByGroup as $group => $students)  {

           $studentGroup = collect($students)->map(function($item)use($department_id, $department_option_id, $group) {

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

}