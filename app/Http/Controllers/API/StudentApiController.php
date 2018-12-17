<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StudentApiRequest;
use App\Models\Student;
use App\Traits\StudentScore;
use App\Utils\FormParamManager;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class StudentApiController extends Controller
{

    use StudentScore; //---trait class

    public function studentScore(StudentApiRequest $request)
    {

        $dataParams = FormParamManager::getFormParams($request);

        $studentAnnualId = $dataParams['student_annual_id'];
        $semesterId = $dataParams['semester_id'];

        if ($semesterId) {
            return ['data' => $this->getStudentScoreBySemester($studentAnnualId, $semesterId)];//20486,1
        } else {

            return ['data' => $this->getStudentScoreBySemester($studentAnnualId, $semesterId = null)];
        }

    }

    public function studentDataFromDB(StudentApiRequest $request)
    {

        $students = DB::table('students')
            ->select('students.id_card', 'students.dob', 'students.name_latin')
            ->get();

        return $students;
    }

    public function studentObject(StudentApiRequest $request)
    {

        $dataParams = FormParamManager::getFormParams($request);
        $studentIdCard = isset($dataParams['student_id_card']) ? $dataParams['student_id_card'] : null;
        $academicYearId = isset($dataParams['academic_year_id']) ? $dataParams['academic_year_id'] : null;
        $student = Student::where('id_card', $studentIdCard)->first();

        if ($academicYearId) {

            $studentAnnuals = $student->studentAnnuals;//->where('academic_year_id', $academicYearId)->get();
            foreach ($studentAnnuals as $studentAnnual) {
                if ($studentAnnual->academic_year_id == $academicYearId) {
                    return $studentAnnual;
                }
            }
        } else {
            $studentAnnuals = $student->studentAnnuals;
            return ($studentAnnuals);
        }
    }

    public function studentScoreAnnually(Request $request)
    {

        $dataParams = FormParamManager::getFormParams($request);
        $studentIdCard = isset($dataParams['student_id_card']) ? $dataParams['student_id_card'] : null;
        $academicYearId = isset($dataParams['academic_year_id']) ? $dataParams['academic_year_id'] : null;
        $student = Student::where('id_card', $studentIdCard)->first();
        $courseAnnualByYears = [];

        if ($academicYearId) {
            $studentAnnuals = $student->studentAnnuals;//->where('academic_year_id', $academicYearId)->get();
            foreach ($studentAnnuals as $studentAnnual) {
                if ($studentAnnual->academic_year_id == $academicYearId) {
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

            foreach ($studentAnnuals as $studentAnnual) {
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

    public function student_program(Request $request)
    {

        $dataParams = FormParamManager::getFormParams($request);
        $studentIdCard = isset($dataParams['student_id_card']) ? $dataParams['student_id_card'] : null;
        if ($studentIdCard != null) {
            return Student::where('id_card', $studentIdCard)->first();
        } else {
            return [];
        }
    }

    public function student_prop(Request $request)
    {

        $dataParams = FormParamManager::getFormParams($request);
        $studentIdCard = isset($dataParams['student_id_card']) ? $dataParams['student_id_card'] : null;

        $studentProp = Student::where('id_card', $studentIdCard)
            ->join('genders', function ($query) {
                $query->on('genders.id', '=', 'students.gender_id');
            })->first();

        return $studentProp;

    }

    /**
     * @param Request $request
     * @return array
     */
    public function studentByDept(Request $request)
    {

        $dataParams = FormParamManager::getArrayFormParams($request);
        $student_id_cards = isset($dataParams['student_id_card']) ? ($dataParams['student_id_card']) : null;
        $academic_year_id = isset($dataParams['academic_year_id']) ? ($dataParams['academic_year_id']) : null;

        $studentByDept = collect(DB::table('students')
            ->join('studentAnnuals', function ($query) use ($student_id_cards, $academic_year_id) {
                $query->on('students.id', '=', 'studentAnnuals.student_id')
                    ->whereIn('students.id_card', $student_id_cards)
                    ->where('studentAnnuals.academic_year_id', '=', DB::table('academicYears')->max('id') /*$academic_year_id*/ /*DB::table('academicYears')->max('id')*/);
            })
            ->select('students.id_card', 'studentAnnuals.department_id', 'students.name_latin', 'studentAnnuals.academic_year_id')
            ->get())
            ->groupBy('department_id')->toArray();

        return ($studentByDept);

    }


    public function studentClassmate(Request $request)
    {

        $dataParams = FormParamManager::getFormParams($request);
        $studentIdCard = isset($dataParams['student_id_card']) ? $dataParams['student_id_card'] : null;

        $student = DB::table('students AS s')
            ->join(DB::raw('(SELECT * FROM ' . '"studentAnnuals"' . ' WHERE academic_year_id = (SELECT MAX(academic_year_id) FROM ' . '"studentAnnuals"' . ')) AS sa'), function ($join) use ($studentIdCard) {
                $join->on('s.id', '=', 'sa.student_id')
                    ->where('s.id_card', '=', $studentIdCard);
            })->first();

        $classmates = DB::table('studentAnnuals')
            ->join('students', function ($query) use ($student) {
                $query->on('students.id', '=', 'studentAnnuals.student_id')
                    ->where('studentAnnuals.department_id', '=', $student->department_id)
                    ->where('studentAnnuals.degree_id', '=', $student->degree_id)
                    ->where('studentAnnuals.grade_id', '=', $student->grade_id)
                    ->where('studentAnnuals.academic_year_id', '=', $student->academic_year_id);
            })->select([
                'students.name_latin', 'students.address', 'students.dob', 'students.email', 'students.id_card'
            ])->orderBy('name_latin')->get();

        return $classmates;

    }

    public function getStudents(Request $request)
    {
        if (isset($request->number_per_page)) {
            $numberPerPage = $request->number_per_page;
        } else {
            $numberPerPage = 10;
        }
        $currentPage = $request->page;

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $students = Student::join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
            ->distinct('studentAnnuals.id');

        if (isset($request->academic_year_id)) {
            $students->where('studentAnnuals.academic_year_id', $request->academic_year_id);
        }

        if (isset($request->department_id)) {
            $students->where('studentAnnuals.department_id', $request->department_id);
        }

        if (isset($request->department_option_id)) {
            $students->where('studentAnnuals.department_option_id', $request->department_option_id);
        }

        if (isset($request->degree_id)) {
            $students->where('studentAnnuals.degree_id', $request->degree_id);
        }

        if (isset($request->grade_id)) {
            $students->where('studentAnnuals.grade_id', $request->grade_id);
        }

        if(isset($request->filtertext)) {
            $students->where('students.name_latin', 'ilike', "%$request->filtertext%")
                ->orWhere('students.name_kh', 'ilike', "%$request->filtertext%");
        }

        $students->select('students.id', 'students.dob', 'students.photo', 'students.email', 'students.name_latin', 'students.name_kh')
            ->orderBy('students.name_latin', 'asc');

        $result = $students->paginate($numberPerPage);

        return ['code' => 1, 'status' => 'success', 'total_page' => $result->lastPage(), 'data' => $result->getCollection()];
    }
}
