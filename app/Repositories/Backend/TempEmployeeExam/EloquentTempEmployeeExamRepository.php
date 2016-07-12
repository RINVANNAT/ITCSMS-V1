<?php

namespace App\Repositories\Backend\TempEmployeeExam;


use App\Exceptions\GeneralException;
use App\Models\Exam;
use Carbon\Carbon;
use DB;

/**
 * Class EloquentEmployeeRepository
 * @package App\Repositories\Backend\Employee
 */
class EloquentTempEmployeeExamRepository implements TempEmployeeExamRepositoryContract
{

    public function getAllStaff($order_by='name_kh', $exam_id) {
        $allStaffs = [];
        $temporaryStaff = DB::table('tempEmployees')
                        ->join('role_temporary_staff_exams', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
                        ->join('roleStaffs', 'roleStaffs.id', '=', 'role_temporary_staff_exams.role_staff_id')
                        ->join('exams', 'exams.id', '=', 'role_temporary_staff_exams.exam_id')
                        ->select('tempEmployees.name_kh', 'exams.name', 'roleStaffs.name')
                        ->where('exams.id', '=', $exam_id)->get();

        $permanentStaff = DB::table('employees')
                        ->join('role_permanent_staff_exams', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
                        ->join('roleStaffs', 'roleStaffs.id', '=', 'role_permanent_staff_exams.role_staff_id')
                        ->join('exams', 'exams.id', '=', 'role_permanent_staff_exams.exam_id')
                        ->select('employees.name_kh', 'exams.name', 'roleStaffs.name')
                        ->where('exams.id', '=', $exam_id)->get();

        foreach ($temporaryStaff as $staff) {
            array_push($allStaffs, $staff->name_kh);
        }
        foreach ($permanentStaff as $perStaff) {
            array_push($allStaffs, $perStaff->name_kh);
        }
        return $allStaffs;
    
    }

    public function getStaffByRole($role_id, $exam_id) {

        $allStaffByRoles = [];
        $temporaryStaffByRoles = DB::table('tempEmployees')
                        ->join('role_temporary_staff_exams', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
                        ->join('roleStaffs', 'roleStaffs.id', '=', 'role_temporary_staff_exams.role_staff_id')
                        ->join('exams', 'exams.id', '=', 'role_temporary_staff_exams.exam_id')
                        ->select('tempEmployees.name_kh', 'exams.name', 'roleStaffs.name')
                        ->where([
                                ['roleStaffs.id', '=', $role_id], 
                                ['exams.id', '=', $exam_id],
                        ])->get();


        $permanentStaffByRoles = DB::table('employees')
                        ->join('role_permanent_staff_exams', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
                        ->join('roleStaffs', 'roleStaffs.id', '=', 'role_permanent_staff_exams.role_staff_id')
                        ->join('exams', 'exams.id', '=', 'role_permanent_staff_exams.exam_id')
                        ->select('employees.name_kh', 'exams.name', 'roleStaffs.name')
                        ->where([
                                ['roleStaffs.id', '=', $role_id],
                                ['exams.id', '=', $exam_id],
                        ])->get();
       
        foreach ($temporaryStaffByRoles as $staff) {
            array_push($allStaffByRoles, $staff->name_kh);
        }
        foreach ($permanentStaffByRoles as $perStaff) {
            array_push($allStaffByRoles, $perStaff->name_kh);
        }
      
        return $allStaffByRoles;

    }

    public function getRoleBytStaff($staff_id, $exam_id) {

        $roleTemporary = DB::table('roleStaffs')
                        ->join('role_temporary_staff_exams', 'roleStaffs.id', '=', 'role_temporary_staff_exams.role_staff_id')
                        ->join('tempEmployees', 'tempEmployees.id', '=', 'role_temporary_staff_exams.temp_employee_id')
                        ->join('exams', 'exams.id', '=', 'role_temporary_staff_exams.exam_id')
                        ->select('roleStaffs.name')
                        ->where([
                                ['tempEmployees.id', '=', $staff_id], 
                                ['exams.id', '=', $exam_id],
                        ])->get();

        $rolePermanent = DB::table('roleStaffs')
                        ->join('role_permanent_staff_exams', 'roleStaffs.id', '=', 'role_permanent_staff_exams.role_staff_id')
                        ->join('employees', 'employees.id', '=', 'role_permanent_staff_exams.employee_id')
                        ->join('exams', 'exams.id', '=', 'role_permanent_staff_exams.exam_id')
                        ->select('roleStaffs.name')
                        ->where([
                                ['employees.id', '=', $staff_id], 
                                ['exams.id', '=', $exam_id],
                        ])->get();

        if( $roleTemporary != null && $rolePermanent == null) {
            return $roleTemporary;
        } else if ( $roleTemporary == null && $rolePermanent != null ) {
            return $rolePermanent;
        }else {
            return ['this is null'];
        }

    }

    public function getAllRoles () {

        $roles = DB::table('roleStaffs')->get();
        dd($roles) ;
    }

    public function create($input) {

        echo 'hello create';
    }

    public function update($id, $input) {

        echo 'hello update';
    }

    public function destroy($id) {

        echo 'hello destroy';
    }

    public function search($name) {

        $val = TempEmployeeExam::where("name_kh","LIKE","%".$name."%")->orWhere("name_latin", "LIKE", "%".$name."%")->get();
        return $val;
    }
}
