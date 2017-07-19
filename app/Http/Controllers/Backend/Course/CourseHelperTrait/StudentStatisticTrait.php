<?php

namespace App\Http\Controllers\Backend\Course\CourseHelperTrait;
use App\Models\AcademicYear;
use App\Models\Enum\GenderEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 5/9/17
 * Time: 11:12 AM
 */
trait StudentStatisticTrait
{

    public function student_statistic_radie(Request $request) {

        $year = AcademicYear::where('id', $request->academic_year_id)->first();

        $studentPassIds = $this->studentProp($request->academic_year_id)->where('students.radie', false)->lists('student_annual_id');
        $studentFailIds = $this->studentProp($request->academic_year_id)->where('students.radie', true)->lists('student_annual_id');

        $studentFail = $this->studentProp($request->academic_year_id)->where('students.radie', true)->get();//->groupBy('gender_id')->toArray();
        $studentPass = $this->studentProp($request->academic_year_id)->where('students.radie', false)->get();//->groupBy('gender_id')->toArray();

        $f_student_pass_ids = $this->studentProp($request->academic_year_id)->where([['students.radie', false], ['gender_id', GenderEnum::F_ID]])->lists('student_annual_id');
        $f_student_pass = collect($this->studentScholarshipProp($f_student_pass_ids))->groupBy('student_annual_id')->toArray();
        $f_student_fail_ids = $this->studentProp($request->academic_year_id)->where([['students.radie', true], ['gender_id', GenderEnum::F_ID]])->lists('student_annual_id');
        $f_student_fail = collect($this->studentScholarshipProp($f_student_fail_ids))->groupBy('student_annual_id')->toArray();


        $scholarshipStudentPass = $this->studentScholarshipProp($studentPassIds);//->groupBy('scholarship_id')->toArray();
        $scholarshipStudentFail =$this->studentScholarshipProp($studentFailIds);//->groupBy('scholarship_id')->toArray();

        return view("backend.course.courseAnnual.print.student_statistic_radie", compact('year','studentFail', 'studentPass', 'scholarshipStudentPass', 'scholarshipStudentFail', 'f_student_pass', 'f_student_pass_ids', 'f_student_fail_ids', 'f_student_fail'));
    }

    /**
     * @param $ids
     * @return mixed
     */

    private function studentScholarshipProp($ids) {

        return DB::table('scholarship_student_annual')
                ->join('scholarships', 'scholarship_student_annual.scholarship_id', '=', 'scholarships.id')
                ->whereIn('scholarship_student_annual.student_annual_id',$ids)
                ->select('scholarships.id as scholarship_id', 'scholarships.code as scholarship_code', 'scholarship_student_annual.student_annual_id')
                ->get();

    }

    /**
     * @param $academicYearId
     * @return mixed
     */
    private function studentProp($academicYearId) {

        $studentRadies = DB::table('students')
            ->join('studentAnnuals', function($query) use($academicYearId) {
                $query->on('students.id', '=', 'studentAnnuals.student_id')
                    ->where('academic_year_id', '=', $academicYearId);
            })
            ->select('students.name_latin', 'students.name_kh', 'students.gender_id', 'studentAnnuals.id as student_annual_id');

        return $studentRadies;
    }

}