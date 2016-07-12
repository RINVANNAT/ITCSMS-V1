<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Backend\Exam\CreateExamRequest;
use App\Http\Requests\Backend\Exam\DeleteExamRequest;
use App\Http\Requests\Backend\Exam\EditExamRequest;
use App\Http\Requests\Backend\Exam\StoreExamRequest;
use App\Http\Requests\Backend\Exam\UpdateExamRequest;
use App\Models\TempEmployeeExam;
use App\Repositories\Backend\TempEmployeeExam\TempEmployeeExamRepositoryContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class employeeExamController extends Controller
{
   private $tempEmpolyeeExams;

   public function __construct( TempEmployeeExamRepositoryContract $tempEmpolyeeExams ) {

   		$this->tempEmpolyeeExams = $tempEmpolyeeExams;
   }

   public function index() {

   		return 'string';
   }

   public function getAll() {

	   	$name = 'vanat';
	   	$tmpEmployeeExam = $this->tempEmpolyeeExams->getAllStaff();
	   	dd($tmpEmployeeExam);

   }

   public function getExaminationStaffByRole (Request $request) {

      $role_id = 1;
      $exam_id = 1;
		$res = $this->tempEmpolyeeExams->getStaffByRole($role_id, $exam_id);
		return $res;
   }

   public function getExaminationStaff () {

      $exam_id = 1;
      $res = $this->tempEmpolyeeExams->getAllStaffWithRoles($order_by='name_kh', $exam_id);
      // $res = $this->tempEmpolyeeExams->getAllStaffWithoutRoles($exam_id);

      return $res;


   }

   public function getRoleStaff () {
      
      $exam_id = 1;
      $staff_id = 3;
      $res = $this->tempEmpolyeeExams->getRoleBytStaff($staff_id, $exam_id);
      return $res;
   }

   public function getAllRoles () {

      $res = $this->tempEmpolyeeExams->getAllRoles();
      return $res;
   }


}
