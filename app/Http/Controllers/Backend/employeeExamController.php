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
        $res = $this->tempEmpolyeeExams->modifyStaffRole($id, $request);
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
                    ->where('tempEmployees.active', '=', true)
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

    public function printViewRoleStaffLists($examId) {

        $roles = $this->tempEmpolyeeExams->printStaffByEachRole($examId);

        return view('backend.exam.print.examination_staff_role', compact('roles'));
    }

    public function viewRoleStaffLists($examId) {

        $res = $this->tempEmpolyeeExams->viewStaffByEachRoleLists($examId);

        return view('backend.exam.includes.popup_view_examination_staff_role', compact('examId', 'res'));

    }

    private function mergeUniqueArray($staffIds) {

        $arrayIds = [];
        $tempStaffWithRoomIds = array_unique($staffIds);

        foreach($tempStaffWithRoomIds as $tempStaffWithRoomId) {
            $arrayIds[] = $tempStaffWithRoomId;
        }

        return $arrayIds;
    }

    private function getRoomByStaff($staffId, $departmentName) {


        if($departmentName == 'Ministry') {
            $tempRooms = DB::table('examRooms')
                ->join('role_temporary_staff_exams', 'role_temporary_staff_exams.room_id', '=', 'examRooms.id')
                ->join('tempEmployees', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
                ->join('buildings', 'rooms.building_id', '=', 'buildings.id')
                ->where([
                    ['tempEmployees.id', '=', $staffId],
                    ['tempEmployees.active', true]
                ])
                ->select('examRooms.name as room_name', 'examRooms.id as room_id', 'buildings.code')
                ->get();

            if($tempRooms) {
                return $tempRooms;
            }
        } else {
            $perRooms = DB::table('examRooms')
                ->join('role_permanent_staff_exams', 'role_permanent_staff_exams.room_id', '=', 'examRooms.id')
                ->join('employees', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
                ->join('buildings', 'examRooms.building_id', '=', 'buildings.id')
                ->where([
                    ['role_permanent_staff_exams.employee_id', '=', $staffId],
                    ['employees.active', true]
                ])
                ->select('examRooms.name as room_name', 'examRooms.id as room_id', 'buildings.code')
                ->get();
            if($perRooms) {
                return $perRooms;
            }
        }

    }

    public function getStaffByRoleCourse($exam_id, Request $request) {


        $roleId = $request->role_id;
        $courseId = $request->course_id;
        $tempStaffWithRoomIds = [];
        $perStaffWithRoomIds = [];
        $perStaffIds = [];
        $tempStaffIds = [];
        $selectedRooms = [];
        $newElementStaffWithRooms = [];
        $staffs = $this->tempEmpolyeeExams->getStaffByRole($roleId, $exam_id);
        $staffWithSelectedRooms = $this->tempEmpolyeeExams->staffWithselectedRooms();

        foreach($staffWithSelectedRooms as $staffWithSelectedRoom) {

            if($staffWithSelectedRoom->department_name != 'Ministry') {

                $perStaffWithRoomIds[] = $staffWithSelectedRoom->staff_id;
            } else {
                $tempStaffWithRoomIds[] = $staffWithSelectedRoom->staff_id;
            }
        }

        $perStaffIds = $this->mergeUniqueArray($perStaffWithRoomIds);
        $tempStaffIds = $this->mergeUniqueArray($tempStaffWithRoomIds);

        if($staffWithSelectedRooms) {
//            dd($staffs);
            foreach($staffs as $staff) {
                $arrayRooms = [];
                $statusPerStaff = 0;
                $statusTempStaff = 0;

                if($staff['department_name'] != 'Ministry') {

                    if($perStaffIds) {
                        foreach($perStaffIds as  $perStaffId) {

                            if($staff['staff_id'] == $perStaffId) {

                                $rooms = $this->getRoomByStaff((int)$perStaffId, $staff['department_name']);
                                if($rooms) {
                                    foreach($rooms as $room) {
                                        $arrayRooms[] = ['room_name' => $room->room_name.''.$room->code, 'room_id' => $room->room_id];
                                    }
                                }

                                $element = array(
                                    'id' => $staff['id'],
                                    'text' => $staff['text'],
                                    'staff_id' => $staff['staff_id'],
                                    'room_name' => $arrayRooms,
                                    'department_name' => $staff['department_name']
                                );

                                $newElementStaffWithRooms[] = $element;
                            } else {
                                $statusPerStaff++;
                            }
                        }

                        if($statusPerStaff == count($perStaffIds)) {

                            $newElementStaffWithRooms[] = $staff;
                        }

                    } else {
                        $newElementStaffWithRooms[] = $staff;
                    }

                } else {

                    if($tempStaffIds) {

                        foreach($tempStaffIds as  $tempStaffId) {

                            if($staff['staff_id'] == $tempStaffId) {

                                $rooms = $this->getRoomByStaff((int)$tempStaffId, $staff['department_name']);
                                if($rooms) {
                                    foreach($rooms as $room) {
                                        $arrayRooms[] = ['room_name' => $room->room_name.''.$room->code, 'room_id' => $room->room_id];
                                    }
                                }
                                $element = array(
                                    'id' => $staff['id'],
                                    'text' => $staff['text'],
                                    'staff_id' => $staff['staff_id'],
                                    'room_name' => $arrayRooms,
                                    'department_name' => $staff['department_name']
                                );

                                $newElementStaffWithRooms[] = $element;
                            } else {
                                $statusTempStaff++;
                            }
                        }

                        if($statusTempStaff == count($tempStaffIds)) {
                            $newElementStaffWithRooms[] = $staff;
                        }
                    } else {

                        $newElementStaffWithRooms[] = $staff;
                    }

                }
            }

            $staffs = $newElementStaffWithRooms;
            return view('backend.exam.includes.partial_staff_by_role', compact('staffs'));
        } else {
            return view('backend.exam.includes.partial_staff_by_role', compact('staffs'));
        }

    }

    public function updateStaffRoom($exam_id, Request $request) {

        $staffIds = $request->staff_id;
        $roomIds = $request->room_id;
        $roleId = $request->role_id;

        $checkTemp = 0;
        $checkPer = 0;

        foreach($staffIds as $staffId) {

            $id = explode('_', $staffId);
            if($id[0] == 'perstaff') {
                $roomByStaff = $this->getRoomByStaff($id[2], $TypeStaff = 'perstaff');

                if($roomByStaff) {
                    foreach($roomIds as $roomId) {
                        $result = $this->tempEmpolyeeExams->insertRolePerStaffExam($exam_id, $id[2], $roleId, $roomId, $request->course_id);
                    }
                } else {
                    $roomId = null;
                    $res = $this->tempEmpolyeeExams->destroyRolePerStaffExam($roleId, $id[2], $exam_id, $roomId);
                    foreach($roomIds as $roomId) {
                        $result = $this->tempEmpolyeeExams->insertRolePerStaffExam($exam_id, $id[2], $roleId, $roomId, $request->course_id);
                    }
                }

                if($result) {
                    $checkPer++;
                }

            } else if($id[0] == 'tmpstaff') {

                $roomByStaff = $this->getRoomByStaff($id[2], $TypeStaff = 'Ministry');

                if($roomByStaff) {
                    foreach($roomIds as $roomId) {
                        $result = $this->tempEmpolyeeExams->insertRoleTempStaffExam($exam_id, $id[2], $roleId, $roomId, $request->course_id);
                    }

                } else{
                    $roomId = null;
                    $tempRes = $this->tempEmpolyeeExams->destroyRoleTempStaffExam($roleId, $id[2], $exam_id, $roomId);
                    foreach($roomIds as $roomId) {
                        $result = $this->tempEmpolyeeExams->insertRoleTempStaffExam($exam_id, $id[2], $roleId, $roomId, $request->course_id);
                    }
                }

                if($result) {
                    $checkTemp++;
                }

            }
        }

        if($checkPer != 0 || $checkTemp != 0) {
            return Response::json(['status' => true]);
        } else {
            return Response::json(['status' => false]);
        }
    }

    public function getRoomByRole($examId, Request $request) {

        $arrayRoomIds = [];
        $roomIds=[] ;
        $arrayRooms = [];
        $roleId = $request->role_id;
        $staffByroles = $this->tempEmpolyeeExams->getStaffByRole($roleId, $examId);

        foreach ($staffByroles as $staffByrole ) {

            $staffId = $staffByrole['staff_id'];
            $departmentName = $staffByrole['department_name'];
            $roomByStaffIds = (object)$this->getRoomByStaff($staffId, $departmentName);

            foreach($roomByStaffIds as $roomByStaffId) {
                $arrayRoomIds[] = $roomByStaffId->room_id;
            }

        }
        $tempArray = array_unique($arrayRoomIds);
        foreach($tempArray as $roomId) {
            $roomIds[] = $roomId;
        }

        $notSelectedRooms = DB::table('examRooms')
            ->join('buildings', 'examRooms.building_id', '=', 'buildings.id')
            ->whereNotIn('examRooms.id', $roomIds)
            ->select('examRooms.name as room_name', 'examRooms.id as room_id', 'buildings.code')
            ->get();

        foreach($notSelectedRooms as $notSelectedRoom) {
            $arrayRooms[] = ['room_name' => $notSelectedRoom->room_name.''.$notSelectedRoom->code, 'room_id' => $notSelectedRoom->room_id];
        }
        return view('backend.exam.includes.partial_room_by_role_staff', compact('arrayRooms'));
    }

    public function getPopUpMessage($examId, Request $request) {

        $staffRole = $request->staff_role_id;
        $staffRole = explode('_',$staffRole);
        $staffType = $staffRole[0];
        $roleId = $staffRole[1];
        $staffId = $staffRole[2];
        $roomName = $staffRole[3];
        $roomId = $request->room_id;

        return view('backend.exam.includes.popup_delete_cancel_room', compact('staffType', 'roleId', 'staffId', 'roomName', 'roomId', 'examId'));
    }

    public function deleteRoomFromStaff($examId, Request $request) {

        $staffType = $request->staff_type;
        $roleId = $request->role_id;
        $staffId =$request->staff_id;
        $roomId = $request->room_id;


//        dd($staffType.'--'.$roleId.'--'.$staffId.'--'.$roomId);

        if($staffType == 'perstaff') {

            $rooms = $this->getRoomByStaff($staffId, $departmentName='perstaff');

            if($rooms) {
                if(count($rooms) > 1) {

                    $del = $this->tempEmpolyeeExams->destroyRolePerStaffExam($roleId, $staffId, $examId, $roomId);
                    if($del) {
                        return Response::json(['status'=> true]);
                    }
                } else if(count($rooms) == 1) {

                    $update = $this->tempEmpolyeeExams->updateRoleStaffPerEmployee($examId, $staffId, $roleId, $roomId = null, $course_id=null);
                    if($update) {
                        return Response::json(['status'=> true]);
                    }
                }
            }

        } else if($staffType == 'tmpstaff') {

            $rooms = $this->getRoomByStaff($staffId, $departmentName='Ministry');

            if($rooms) {
                if(count($rooms) > 1) {

                    $del = $this->tempEmpolyeeExams->destroyRoleTempStaffExam($roleId, $staffId, $examId, $roomId);

                    if($del) {
                        return Response::json(['status'=> true]);
                    }
                } else if(count($rooms) == 1) {
                    $update = $this->tempEmpolyeeExams->updateRoleStaffTempEmployee($examId, $staffId, $roleId, $roomId= null, $course_id= null);
                    if($update) {
                        return Response::json(['status'=> true]);
                    }
                }
            }

        } else {
            return Response::json(['status'=> false]);
        }
    }
    public function staffRoleRoomExport($examId) {

        $allStaffRoleRoomExport = [];
        $concatRoom = [];
        $data = [];

        $roles =  $this->tempEmpolyeeExams->getRoles();
        if($roles) {

            foreach($roles as $role) {
                $staffs = $this->tempEmpolyeeExams->getStaffByRole($role->id, $examId);

                if($staffs) {

                    foreach($staffs as $staff) {
                        $rooms = $this->getRoomByStaff($staff['staff_id'], $staff['department_name']);

                        if($rooms) {

                            foreach($rooms as $room) {
                                $concatRoom[] =$room->room_name.''.$room->code.',';
                            }
                            $allRooms = trim(implode(' ', $concatRoom), ",");

                            $element= array(

                                'Staff Name'    =>  $staff['text'],
                                'Role'          =>  $role->name,
                                'Department'    =>  $staff['department_name'],
                                'Room'          =>  $allRooms
                            );

                            $data[] = $element;
                        }

                    }

                }
            }
            $fields= ['Staff Name', 'Role', 'Department', 'Room'];
            $title = 'List Staff Role && Room';
            $alpha = [];
            $letter = 'A';
            while ($letter !== 'AAA') {
                $alpha[] = $letter++;
            }
            Excel::create('Staff Role Room', function($excel) use ($data, $title,$alpha,$fields) {


                $excel->setTitle('List Staff Role && Room');
                $excel->setCreator('Department of Study & Student Affair')
                    ->setCompany('Institute of Technology of Cambodia');
                $excel->sheet('Staff_role_room', function($sheet) use($data,$title,$alpha,$fields) {

                    $sheet->fromArray($data);
                });

            })->download('csv');
        }


    }
}
