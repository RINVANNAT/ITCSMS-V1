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
use App\Repositories\Backend\DepartmentEmployeeExamPosition\DepartmentEmployeeExamPositionRepositoryContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class employeeExamController extends Controller
{
    private $tempEmpolyeeExams;
    private $departmentEmployees;

    public function __construct(TempEmployeeExamRepositoryContract $tempEmpolyeeExams, DepartmentEmployeeExamPositionRepositoryContract $departmentEmployees)
    {

        $this->departmentEmployees = $departmentEmployees;
        $this->tempEmpolyeeExams = $tempEmpolyeeExams;
    }

    public function getAll()
    {

        $name = 'vanat';
        $tmpEmployeeExam = $this->tempEmpolyeeExams->getAllStaff();
        dd($tmpEmployeeExam);

    }

    public function getExaminationStaffByRole(Request $request, $id)
    {

        $role_id = explode('_', $_GET['id'])[1];
        $res = $this->tempEmpolyeeExams->getStaffByRole($role_id, $id);
        return $res;
    }

    public function getExaminationStaff($id)
    {

        $res = $this->tempEmpolyeeExams->getAllStaffWithRoles($order_by = 'name_kh', $id);
        // $res = $this->tempEmpolyeeExams->getAllStaffWithoutRoles($exam_id);

        return Response::json($res);
    }

    public function getAllRoles($id)
    {
        $res = $this->tempEmpolyeeExams->getAllRoles($id);
        $result = [];
        foreach ($res as $a) {
            array_push($result, $a);
        }
        return Response::json($result);
    }

    public function getRoles()
    {
        $res = $this->tempEmpolyeeExams->getRoles();
        return $res;
    }

    public function getAllDepartments($id)
    {

        $res = $this->departmentEmployees->getAllDepartements($id);
        return Response::json($res);
    }

    public function getPositionByDepartments($id)
    {
        $department_id = explode('_', $_GET['id'])[1];
//        $department_id = (int)($_GET['department_id']);

        $res = $this->departmentEmployees->getAllPositionByDepartements($department_id, $id);
        $result = [];
        foreach ($res as $a) {
            array_push($result, $a);
        }
        return Response::json($result);
    }

    public function getStaffWithoutRoleByPositions($id)
    {
        $node_id = explode('_', $_GET['id']);
        $position_id = $node_id[2];
        $selectedDepartment_id = $node_id[1];

        $res = $this->departmentEmployees->getAllStaffWithoutRoleByPosition($selectedDepartment_id, $position_id, $role_id = null, $id);

        return Response::json($res);
    }

    public function saveStaffRoles($id, Request $request)
    {
        $res = $this->departmentEmployees->saveStaffForEachRole($id, $request);

        return $res;

    }

    public function addNewRole(Request $request)
    {
        $res = $this->tempEmpolyeeExams->create($request);

        return $res;
    }

    public function deleteRoleNode ($id, Request $request) {
        $res = $this->tempEmpolyeeExams->destroy($id, $request);
        return $res;
    }

    public function changeRoleStaffs($id, Request $request) {
        $res = $this->tempEmpolyeeExams->update($id, $request);
        return $res;
    }

    public function requestImportTempEmployees($exam_id, Request $request){

        return view('backend.exam.includes.import_temp_employee', compact('exam_id'));

    }

    public function importTempEmployees($exam_id, Request $request){

        $now = Carbon::now()->format('Y_m_d_H');

        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', "tempEmployee_".$import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/tempEmployee_'.$import;

            DB::beginTransaction();
            try{
                Excel::filter('chunk')->load($storage_path)->chunk(100, function($results){

                    $results->each(function($row) {
                        $tempEmployee = $this->tempEmpolyeeExams->createImportedTempEmployees($row->toArray());
                    });

                });

            } catch(Exception $e){
                DB::rollback();
            }
            DB::commit();
            $status = true;
            return view('backend.exam.includes.after_import_success', compact('status'));
        } else {
            $status = false;
            return view('backend.exam.includes.after_import_success', compact('status'));
        }
    }

    public function exportTempEmployees($exam_id) {

        $data = [];
        $tempEmployees = DB::table('tempEmployees')
                    ->select('id', 'name_kh', 'name_latin', 'email', 'phone', 'birthdate', 'address', 'gender_id', 'academic_year_id')
                    ->get();
        foreach( $tempEmployees as $tempEmployee) {
            $gender = DB::table('genders')->where('id', $tempEmployee->gender_id)->select('name_en')->first();
            $academicYear = DB::table('academicYears')->where('id',$tempEmployee->academic_year_id)->select('name_latin')->first();
            $birthDate = explode(" ",$tempEmployee->birthdate);
            if($gender) {
                if($academicYear) {
                    $element = array(
                        'Name khmer'         => $tempEmployee->name_kh,
                        'Name Latin'         => $tempEmployee->name_latin,
                        'E-mail'             => $tempEmployee->email,
                        'Phone'              => $tempEmployee->phone,
                        'Birth Date'         => $birthDate[0],
                        'Address'            => $tempEmployee->address,
                        'Gender'             => $gender->name_en,
                        'Academic Year'      => $academicYear->name_latin
                    );
                    $data[] = $element;
                }

            }

        }
        $fields= ['Name khmer', 'Name Latin', 'Email', 'Phone', 'Birth Date', 'Address', 'Gender', 'Academic Year'];
        $title = 'List of Temporary Staffs For Entrance Examination';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
            Excel::create('temp-employees', function($excel) use ($data, $title,$alpha,$fields) {


            $excel->setTitle('List of Temporary Staffs For Entrance Examination');
            $excel->setCreator('Department of Study & Student Affair')
                ->setCompany('Institute of Technology of Cambodia');
            $excel->sheet('Sheetname', function($sheet) use($data,$title,$alpha,$fields) {

                $sheet->fromArray($data);
            });

        })->download('csv');
    }

}
