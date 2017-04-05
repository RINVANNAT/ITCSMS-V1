<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StudentApiRequest;
use App\Traits\StudentScore;
use Illuminate\Http\Response;
use App\Utils\FormParamManager;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

class StudentApiController extends Controller
{

    use StudentScore; //---trait class

    public function studentScore(StudentApiRequest $request) {

        $dataParams = FormParamManager::getFormParams($request);

        $studentAnnualId = $dataParams['student_annual_id'];
        $semesterId = $dataParams['semester_id'];

        if($semesterId) {
            return ['data' => $this->getStudentScoreBySemester($studentAnnualId, $semesterId)];//20486,1
        } else {

            return ['data' => $this->getStudentScoreBySemester($studentAnnualId, $semesterId=null)];
        }

    }
    public function studentDataFromDB(StudentApiRequest $request) {

        $students = DB::table('students')
            ->select('students.id_card', 'students.dob', 'students.name_latin')
            ->get();

        return $students;
    }

    public function studentObject(StudentApiRequest $request) {

        $dataParams = FormParamManager::getFormParams($request);
        $studentIdCard = isset($dataParams['student_id_card'])?$dataParams['student_id_card']:null;
        $academicYearId = isset($dataParams['academic_year_id'])?$dataParams['academic_year_id']:null;
        $student = Student::where('id_card', $studentIdCard)->first();

        if($academicYearId) {

            $studentAnnuals = $student->studentAnnuals;//->where('academic_year_id', $academicYearId)->get();
            foreach($studentAnnuals as $studentAnnual) {
                if($studentAnnual->academic_year_id == $academicYearId) {
                    return $studentAnnual;
                }
            }
        } else {
            $studentAnnuals = $student->studentAnnuals;
            return ($studentAnnuals);
        }
    }

    public function studentScoreAnnually(Request $request) {

        $dataParams = FormParamManager::getFormParams($request);
        $studentIdCard = isset($dataParams['student_id_card'])?$dataParams['student_id_card']:null;
        $academicYearId = isset($dataParams['academic_year_id'])?$dataParams['academic_year_id']:null;
        $student = Student::where('id_card', $studentIdCard)->first();
        $courseAnnualByYears = [];

        if($academicYearId) {
            $studentAnnuals = $student->studentAnnuals;//->where('academic_year_id', $academicYearId)->get();
            foreach($studentAnnuals as $studentAnnual) {
                if($studentAnnual->academic_year_id == $academicYearId) {
                    $courseAnnualByYear = $this->getStudentScoreBySemester($studentAnnual->id, $semesterId = null);
                    return [
                        'student' => $student,
                        'student_annual' => $studentAnnual,
                        'course_score' => $courseAnnualByYear
                    ];
                }
            }

        } else {
            $studentAnnuals = $student->studentAnnuals;

            foreach($studentAnnuals as $studentAnnual) {
                $studentScoreEachYear = $this->getStudentScoreBySemester($studentAnnual->id, $semesterId = null);
                $courseAnnualByYears[$studentAnnual->academic_year_id] = $studentScoreEachYear;
            }

            return [
                'student' => $student,
                'student_annual' => $studentAnnuals,
                'course_score' => $courseAnnualByYears
            ];
        }
    }




}
