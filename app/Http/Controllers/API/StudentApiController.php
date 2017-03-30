<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StudentApiRequest;
use App\Traits\StudentScore;
use Illuminate\Http\Response;
use App\Utils\FormParamManager;

class StudentApiController extends Controller
{

    use StudentScore; //---trait class

    public function studentScore(StudentApiRequest $request) {

        $dataParams = FormParamManager::getFormParams($request);

        $studentAnnualId = $dataParams['student_annual_id'];
        $semesterId = $dataParams['semester_id'];

        if($semesterId) {
            return $this->getStudentScoreBySemester($studentAnnualId, $semesterId);//20486,1
        } else {

//            dd($this->getStudentScoreBySemester($studentAnnualId, $semesterId=null));
            return ['data' => $this->getStudentScoreBySemester($studentAnnualId, $semesterId=null)];
        }


    }
}
