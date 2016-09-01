<?php

namespace App\Repositories\Backend\TempEmployeeExam;


use App\Exceptions\GeneralException;
use App\Models\Exam;
use App\Models\RoleStaff;
use App\Models\Employee;
use App\Models\TempEmployee;
use App\Models\Room;
use Carbon\Carbon;
use DB;

use App\Repositories\Backend\Employee\EloquentEmployeeRepository;
use Illuminate\Support\Facades\Response;

/**
 * Class EloquentEmployeeRepository
 * @package App\Repositories\Backend\Employee
 */
class EloquentTempEmployeeExamRepository implements TempEmployeeExamRepositoryContract
{

    public function getAllStaffWithRoles($order_by = 'name_kh', $exam_id)
    {
        $staffs = $this->getAllEmployeesFromDatabase($exam_id);
        $temporaryStaff = $staffs[0];
        $permanentStaff = $staffs[1];
        $allStaffs = [];
        foreach ($temporaryStaff as $staff) {

            $element = array(
                "id" => 'tmpstaff_' . $staff->id,
                "text" => $staff->name_kh,
                "children" => true,
                "type" => "staff"
            );
            array_push($allStaffs, $element);

        }
        foreach ($permanentStaff as $perStaff) {

            $element = array(
                "id" => 'perstaff_' . $perStaff->id,
                "text" => $perStaff->name_kh,
                "children" => true,
                "type" => "staff"
            );
            array_push($allStaffs, $element);
        }

        return $allStaffs;

    }

    private function getAllEmployeesFromDatabase($exam_id)
    {
        $temporaryStaff = DB::table('tempEmployees')
            ->join('role_temporary_staff_exams', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
            ->join('roleStaffs', 'roleStaffs.id', '=', 'role_temporary_staff_exams.role_staff_id')
            ->join('exams', 'exams.id', '=', 'role_temporary_staff_exams.exam_id')
            ->select('tempEmployees.name_kh', 'tempEmployees.id', 'exams.name', 'roleStaffs.name as role_name', 'roleStaffs.id as role_id')
            ->where([
                ['exams.id', '=', $exam_id],
                ['tempEmployees.active', '=', true]
            ])->get();

        $permanentStaff = DB::table('employees')
            ->join('role_permanent_staff_exams', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
            ->join('roleStaffs', 'roleStaffs.id', '=', 'role_permanent_staff_exams.role_staff_id')
            ->join('exams', 'exams.id', '=', 'role_permanent_staff_exams.exam_id')
            ->select('employees.name_kh', 'employees.id', 'exams.name', 'roleStaffs.name as role_name', 'roleStaffs.id as role_id')
            ->where([
                ['exams.id', '=', $exam_id],
                ['employees.active', '=', true]
            ])->get();

        return array($permanentStaff, $temporaryStaff);
    }

    public function getEmployeeHaveRoleIds($exam_id)
    {

        // index: 0 for permanent employee
        //index: 1 for temporary employee

        $permanentEmployeeIds = [];
        $temporaryEmployeeIds = [];

        $temporaryAndPermanentStaffs = $this->getAllEmployeesFromDatabase($exam_id);
        foreach($temporaryAndPermanentStaffs[0] as $permanentStaff) {
            array_push($permanentEmployeeIds, $permanentStaff->id);
        }
        foreach($temporaryAndPermanentStaffs[1] as $temporaryStaff) {
            array_push($temporaryEmployeeIds, $temporaryStaff->id);
        }
        return array( $permanentEmployeeIds, $temporaryEmployeeIds);

    }

    public function getStaffByRole($role_id, $exam_id)
    {

        $allStaffByRoles = [];
        $arrayStaffByRoles = [];
        $temporaryStaffByRoles = DB::table('tempEmployees')
            ->join('role_temporary_staff_exams', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
            ->join('roleStaffs', 'roleStaffs.id', '=', 'role_temporary_staff_exams.role_staff_id')
            ->join('exams', 'exams.id', '=', 'role_temporary_staff_exams.exam_id')
            ->select('tempEmployees.name_kh', 'tempEmployees.id', 'exams.name', 'roleStaffs.name', 'roleStaffs.id as role_id')
            ->where([
                ['roleStaffs.id', '=', $role_id],
                ['exams.id', '=', $exam_id],
                ['tempEmployees.active', '=', true]
            ])->get();


        $permanentStaffByRoles = DB::table('employees')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->join('role_permanent_staff_exams', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
            ->join('roleStaffs', 'roleStaffs.id', '=', 'role_permanent_staff_exams.role_staff_id')
            ->join('exams', 'exams.id', '=', 'role_permanent_staff_exams.exam_id')
            ->select('employees.name_kh', 'employees.id', 'departments.name_en as department_name', 'exams.name', 'roleStaffs.name', 'roleStaffs.id as role_id')
            ->where([
                ['roleStaffs.id', '=', $role_id],
                ['exams.id', '=', $exam_id],
                ['employees.active', '=', true]
            ])->get();


        foreach ($temporaryStaffByRoles as $temporaryStaffByRole) {
            $element = array(
                "id" => 'tmpstaff_' . $temporaryStaffByRole->role_id . '_' . $temporaryStaffByRole->id,
                "text" => $temporaryStaffByRole->name_kh,
                "children" => false,
                "type" => "staff",
                "staff_id" => $temporaryStaffByRole->id,
                "room_name" => '',
                "department_name" => 'Ministry'
            );

            array_push($allStaffByRoles, $element);
        }

        foreach ($permanentStaffByRoles as $permanentStaffByRole) {
            $element = array(
                "id" => 'perstaff_' . $permanentStaffByRole->role_id . '_' . $permanentStaffByRole->id,
                "text" => $permanentStaffByRole->name_kh,
                "children" => false,
                "type" => "staff",
                "staff_id" => $permanentStaffByRole->id,
                "room_name" => '',
                "department_name" =>$permanentStaffByRole->department_name
            );

            array_push($allStaffByRoles, $element);
        }


        $allStaffByRoles = array_map("unserialize", array_unique(array_map("serialize", $allStaffByRoles)));

        foreach($allStaffByRoles as $allStaffByRole) {
            $arrayStaffByRoles[] = $allStaffByRole;
        }

        return $arrayStaffByRoles;

    }

    public function getRoleBytStaff($staff_id, $exam_id)
    {

        $roleTemporary = DB::table('roleStaffs')
            ->join('role_temporary_staff_exams', 'roleStaffs.id', '=', 'role_temporary_staff_exams.role_staff_id')
            ->join('tempEmployees', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
            ->join('exams', 'exams.id', '=', 'role_temporary_staff_exams.exam_id')
            ->select('roleStaffs.name')
            ->where([
                ['tempEmployees.id', '=', $staff_id],
                ['exams.id', '=', $exam_id],
                ['tempEmployees.active', '=', true]
            ])->get();

        $rolePermanent = DB::table('roleStaffs')
            ->join('role_permanent_staff_exams', 'roleStaffs.id', '=', 'role_permanent_staff_exams.role_staff_id')
            ->join('employees', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
            ->join('exams', 'exams.id', '=', 'role_permanent_staff_exams.exam_id')
            ->select('roleStaffs.name')
            ->where([
                ['employees.id', '=', $staff_id],
                ['exams.id', '=', $exam_id],
                ['employees.active', '=', true]
            ])->get();

        if ($roleTemporary != null && $rolePermanent == null) {
            return $roleTemporary;
        } else if ($roleTemporary == null && $rolePermanent != null) {
            return $rolePermanent;
        } else {
            return ['this is null'];
        }

    }

    public function getAllRoles($exam_id)
    {

        $roles = [];
        $roleInExam = $this->getAllEmployeesFromDatabase($exam_id);
        $roleTempStaffs = $roleInExam[0];
        $rolePerStaffs = $roleInExam[1];
//        dd($roleInExam);
        foreach ($roleTempStaffs as $roleTempStaff) {
            $element = array(
                "id" => 'role_' . $roleTempStaff->role_id,
                "text" => $roleTempStaff->role_name,
                "children" => true,
                "type" => "role"
            );

            array_push($roles, $element);
        }

        foreach ($rolePerStaffs as $rolePerStaff) {
            $element = array(
                "id" => 'role_' . $rolePerStaff->role_id,
                "text" => $rolePerStaff->role_name,
                "children" => true,
                "type" => "role"
            );

            array_push($roles, $element);
        }


        $roles = array_map("unserialize", array_unique(array_map("serialize", $roles)));
        //$roles = array_unique($roles, SORT_REGULAR);

        return $roles;

    }

    public function getRoles()
    {
        $roles = RoleStaff::all();

        return ($roles);
    }


    public function createImportedTempEmployees($tempEmployees) {

        $academicYearId = DB::table('academicYears')
            ->where([
                ['name_latin', $tempEmployees['academic_year']],
                ['academicYears.active', '=', true]
            ])
            ->select('id')->first();

        if($academicYearId) {
            if (TempEmployee::where([
                ['name_kh', $tempEmployees['name_khmer']],
                ['academic_year_id', $academicYearId->id],
                ['active', '=', true ]
            ])->first()) {
                throw new GeneralException(trans('exceptions.backend.general.already_exists'));
//            return ['status'=>false];
            }
        }

        $genderId = DB::table('genders')
            ->where([
                ['name_en', $tempEmployees['gender']],
                ['genders.active', '=', true]
            ])
            ->select('id')->first();

        $tempEmployee = new TempEmployee;

        if(isset($tempEmployees['name_khmer']))  $tempEmployee->name_kh = $tempEmployees['name_khmer'];
        if(isset($tempEmployees['name_latin']))$tempEmployee->name_latin = $tempEmployees['name_latin'];
        if(isset($tempEmployees['e_mail']))$tempEmployee->email = $tempEmployees['e_mail'];

        if(isset($tempEmployees['address']))$tempEmployee->address = $tempEmployees['address'];

        if(isset($tempEmployees['phone']))$tempEmployee->phone = $tempEmployees['phone'];

        if(isset($tempEmployees['birth_date'])) $tempEmployee->birthdate =  date('Y-m-d', strtotime($tempEmployees['birth_date']));
        $tempEmployee->gender_id = $genderId->id;
        $tempEmployee->academic_year_id = $academicYearId->id;

        $tempEmployee->created_at = Carbon::now();

        if($tempEmployee->save()){
            //UserLog

            UserLog::log([
                'model' => 'TempEmployee',
                'action'   => 'Import', // Import, Create, Delete, Update
                'data'     => $tempEmployee->id, // if it is create action, store only the new id.
            ]);

            return response()->json(['status' => 'import_temp_employee_success',
                'temp_employee_name' => $tempEmployee->name_kh,
                'temp_employee_id' => $tempEmployee->id
            ]);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.rooms.create_error'));

//        dd($tempEmployees);
    }

    public function create($request)
    {
        if (RoleStaff::where('name', $request->role_name)->first()) {
            throw new GeneralException(trans('exceptions.backend.general.already_exists'));
        }
        $role = new RoleStaff;
        $role->name = $request->role_name;
        $role->description = $request->description;
        $role->save();

        if( $role->save()) {
            UserLog::log([
                'model' => 'RoleStaff',
                'action'   => 'Create', // Import, Create, Delete, Update
                'data'     => $role->id, // if it is create action, store only the new id.
            ]);
        }
        return response()->json(['status' => 'add_role_success',
                                 'role_name' => $role->name,
                                 'role_id' => $role->id
                                ]);
    }

    public function modifyStaffRole($exam_id, $request)
    {

        $deleteFirst = $this->destroy($exam_id, $request);

        $staffIds = json_decode($request->staff_ids);
        $roleChangeId = $request->role_id;

        foreach ($staffIds as $staffId) {
            $staffRoleIds = explode('_', $staffId);
            if($staffRoleIds[0] == 'perstaff') {
                $employeeId = $staffRoleIds[2];

                $res = $this->insertRolePerStaffExam($exam_id, $employeeId, $roleChangeId, $roomId = null, $courseId=null);

            } else {
                $res = [];
            }
            if($staffRoleIds[0] == 'tmpstaff') {
                $tempEmployeeId = $staffRoleIds[2];
                $resTemp= $this->insertRoleTempStaffExam($exam_id, $tempEmployeeId, $roleChangeId, $roomId = null, $courseId=null);
            } else {
                $resTemp = [];
            }
        }
        return $this->checkedResponse($res, $resTemp);

    }

    public function destroy($id,$request)
    {
        $roomId = null;

        $staffIds = json_decode($request->staff_ids);
//        dd($staffIds);
        if($staffIds !==[]) {
            foreach ($staffIds as $staffId) {
                $employeeRoleIds = explode('_', $staffId);

                if ($employeeRoleIds[0] == 'perstaff') {
                    $employeeId = $employeeRoleIds[2];
                    $roleId = $employeeRoleIds[1];
                    $res = $this->destroyRolePerStaffExam($roleId, $employeeId, $id, $roomId);
                } else {
                    $res = [];
                }
                if($employeeRoleIds[0] == 'tmpstaff') {
                    $tempEmployeeId = $employeeRoleIds[2];
                    $roleId = $employeeRoleIds[1];
                    $resTemp = $this->destroyRoleTempStaffExam($roleId, $tempEmployeeId, $id, $roomId);
                } else {
                    $resTemp = [];
                }
            }
            return $this->checkedResponse($res, $resTemp);
        }
        return response()->json(['status' => false]);
    }

    public function destroyRoleTempStaffExam($roleId, $tempEmployeeId, $examId, $roomId) {

        if($roomId) {
            $resTemp = DB::table('role_temporary_staff_exams')
                ->where([
                    ['role_staff_id', '=', $roleId],
                    ['exam_id', '=', $examId],
                    ['temp_employee_id', '=', $tempEmployeeId],
                    ['room_id', '=', $roomId]
                ])->delete();

            return $resTemp;
        } else {

            $resTemp = DB::table('role_temporary_staff_exams')
                ->where([
                    ['role_staff_id', '=', $roleId],
                    ['exam_id', '=', $examId],
                    ['temp_employee_id', '=', $tempEmployeeId]
                ])->delete();

            return $resTemp;
        }



    }

    public function destroyRolePerStaffExam($roleId, $employeeId, $examId, $roomId) {

        if($roomId) {
            $res = DB::table('role_permanent_staff_exams')
                ->where([
                    ['role_staff_id', '=', $roleId],
                    ['exam_id', '=', $examId],
                    ['employee_id', '=', $employeeId],
                    ['room_id', '=', $roomId]
                ])->delete();

            return $res;

        } else {

            $res = DB::table('role_permanent_staff_exams')
                ->where([
                    ['role_staff_id', '=', $roleId],
                    ['exam_id', '=', $examId],
                    ['employee_id', '=', $employeeId]
                ])->delete();

            return $res;
        }


    }

    public function search($name)
    {

        $val = TempEmployeeExam::where("name_kh", "LIKE", "%" . $name . "%")->orWhere("name_latin", "LIKE", "%" . $name . "%")->get();
        return $val;
    }

    private function checkedResponse($checkedRes,$checkedResTemp) {
        if($checkedRes || $checkedResTemp) {
            return Response::json(['status' => true]);
        } else {
            return Response::json(['status' => false]);
        }
    }


    public function viewStaffByEachRoleLists($examId) {

        $roles = $this->getRoles();
        $course = DB::table('entranceExamCourses')
            ->where([
                ['entranceExamCourses.exam_id', $examId],
                ['entranceExamCourses.active', '=', true]
            ])
            ->select('entranceExamCourses.name_en as course_name','entranceExamCourses.id as course_id')
            ->get();

        return array($roles, $course);
    }

    public function printStaffByEachRole($examId) {

        $allStaffRoles = [];
        $controllers = [];
        $roles = $this->getRoles();
        $rooms = $this->getNotSelectedRooms();

        return $controllers;

    }

    private function getNotSelectedRooms() {

        $arrayRooms = [];
        $selectedRoomIds = [];

        $staffWithselectedRooms = $this->staffWithselectedRooms();

        foreach($staffWithselectedRooms as $staffWithselectedRoom) {

            $selectedRoomIds[] = $staffWithselectedRoom->room_id;
        }

        $rooms = DB::table('examRooms')
            ->join('buildings', 'rooms.building_id', '=', 'buildings.id')
            ->whereNotIn('examRooms.id', $selectedRoomIds)
            ->select('examRooms.name as room_name', 'examRooms.id as room_id', 'buildings.code')
            ->get();

        foreach($rooms as $room) {
            $arrayRooms[] = ['room_name' => $room->room_name.''.$room->code, 'room_id' => $room->room_id];
        }
        return $arrayRooms;
    }

    public function staffWithselectedRooms() {

        $allstaffWithselectedRooms = [];

        $roomForTempStaffs = DB::table('examRooms')
            ->join('role_temporary_staff_exams', 'examRooms.id', '=', 'role_temporary_staff_exams.room_id')
            ->join('tempEmployees', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
            ->join('buildings', 'buildings.id', '=', 'examRooms.building_id')
            ->where([
                ['tempEmployees.active', '=', true]
            ])
            ->select('tempEmployees.name_kh as staff_name ', 'tempEmployees.id as staff_id', 'examRooms.name as room_name', 'examRooms.id as room_id', 'buildings.code as building_code')
            ->get();

        $roomForPermanentStaffs = DB::table('examRooms')
            ->join('role_permanent_staff_exams', 'examRooms.id', '=', 'role_permanent_staff_exams.room_id')
            ->join('employees', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
            ->join('buildings', 'buildings.id', '=', 'examRooms.building_id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->where([
                ['employees.active', '=', true]
            ])
            ->select('employees.name_kh as staff_name ', 'employees.id as staff_id', 'examRooms.name as room_name', 'examRooms.id as room_id', 'buildings.code as building_code', 'departments.name_en as department_name')
            ->get();

        if($roomForTempStaffs) {

            foreach($roomForTempStaffs as $roomForTempStaff ) {
                $element = (object)([
                   'room_name'      => $roomForTempStaff->room_name.''.$roomForTempStaff->building_code,
                    'room_id'       => $roomForTempStaff->room_id,
                    'staff_name'    => $roomForTempStaff->staff_name,
                    'staff_id'      => $roomForTempStaff->staff_id,
                    'department_name'=> 'Ministry'
                ]);
                $allstaffWithselectedRooms[] = $element;
            }
        }

        if($roomForPermanentStaffs) {

            foreach($roomForPermanentStaffs as $roomForPermanentStaff ) {
                $element = (object)([
                    'room_name'     => $roomForPermanentStaff->room_name.''. $roomForPermanentStaff->building_code,
                    'room_id'       => $roomForPermanentStaff->room_id,
                    'staff_name'    => $roomForPermanentStaff->staff_name,
                    'staff_id'      => $roomForPermanentStaff->staff_id,
                    'department_name' => $roomForPermanentStaff->department_name
                ]);
                $allstaffWithselectedRooms[] = $element;
            }
        }
        return $allstaffWithselectedRooms;
    }

    public function updateRoleStaffTempEmployee($examId, $tempEmployeeId, $roleChangeId, $roomId, $course_id)
    {
        $res = DB::table('role_temporary_staff_exams')
            ->where([
                ['exam_id', '=', $examId],
                ['temp_employee_id', '=', $tempEmployeeId]
            ])->update(['role_staff_id'=> (int)$roleChangeId, 'room_id' => $roomId, 'entrance_exam_course_id' => $course_id]);

        return $res;
    }

    public function updateRoleStaffPerEmployee($examId, $employeeId, $roleChangeId, $roomId, $course_id)
    {
        $res = DB::table('role_permanent_staff_exams')
            ->where([
                ['exam_id', '=', $examId],
                ['employee_id', '=', $employeeId]
            ])->update(['role_staff_id'=> (int)$roleChangeId, 'room_id' => $roomId, 'entrance_exam_course_id' => $course_id]);

        return $res;
    }


    public function insertRoleTempStaffExam($examId, $tempEmployeeId, $roleId, $roomId, $courseId) {

        $insert = DB::table('role_temporary_staff_exams')->insert([

                'role_staff_id'             => $roleId,
                'exam_id'                   => $examId ,
                'temp_employee_id'          => $tempEmployeeId,
                'room_id'                   => $roomId,
                'entrance_exam_course_id'   => $courseId
        ]);

        return $insert;
    }

    public function insertRolePerStaffExam($examId, $employeeId, $roleId, $roomId, $courseId) {

       $insert =  DB::table('role_permanent_staff_exams')->insert([

            'role_staff_id'             => $roleId,
            'exam_id'                   => $examId ,
            'employee_id'               => $employeeId,
            'room_id'                   => $roomId,
            'entrance_exam_course_id'   => $courseId
        ]);

        return $insert;

    }
}
