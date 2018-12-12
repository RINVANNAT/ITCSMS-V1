<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

/**
 * Trait OptionTimetableTrait
 *
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait OptionTimetableTrait
{
    public function getRooms()
    {
        try {
            $rooms = Room::join('buildings', 'buildings.id', '=', 'rooms.building_id')
                ->select([
                    DB::raw("CONCAT(buildings.code, '-', rooms.name) as code"),
                    'rooms.id as id'
                ])->get();
            return message_success($rooms);
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function get_weeks(Request $request)
    {
        $this->validate($request, [
            'semester_id' => 'required'
        ]);
        try {
            $weeks = Week::where('semester_id', $request->semester_id)->get();
            return ['status' => true, 'weeks' => $weeks];
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function get_options()
    {
        try {
            $dept_ids = [2, 3, 5, 6];
            $department_id = request('department_id');
            $options = DepartmentOption::where('department_id', $department_id)->get();
            if (in_array($department_id, $dept_ids)) {
                $additional_option = [
                    'id' => '',
                    'name_kh' => '',
                    'name_en' => '',
                    'name_fr' => '',
                    'code' => '',
                    'active' => true,
                    'created_at' => Carbon::today(),
                    'updated_at' => Carbon::today(),
                    'department_id' => 10,
                    'degree_id' => 1,
                    'create_uid' => 10,
                    'write_uid' => 10
                ];
                $options = collect($options)->push($additional_option);
            }
            if (isset($department_id)) {
                return ['status' => true, 'options' => $options];
            }
        } catch (\Exception $exception) {
            return ['status' => false];
        }
    }

    public function get_grades(Request $request)
    {
        $this->validate($request, [
            'department_id'
        ]);
        try {
            $result = [
                'status' => true,
                'grades' => [],
            ];
            $departmentId = $request->department_id;
            if ($departmentId == 8) {
                $result['grades'] = Grade::where('id', '<=', 2)->orderBy('id')->get();
            } else if ($departmentId == 12) {
                $result['grades'] = Grade::where('id', '>', 1)->orderBy('id')->get();
            } else {
                $result['grades'] = Grade::orderBy('id')->get();
            }
            return $result;
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function get_groups()
    {
        $tmpGroups = Group::get()->toArray();
        $groups = sort_groups($tmpGroups);
        return [
            'code' => 200, 'status' => true, 'groups' => $groups
        ];
    }

    public function get_employees()
    {
        $query = request('query');
        $employees = Employee::join('genders', 'employees.gender_id', '=', 'genders.id')
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->where(function ($sql) use ($query) {
                if (isset($query) && !is_null($query) && $query != '') {
                    $sql->orWhere('employees.name_kh', 'ilike', '%' . $query . '%')
                        ->orWhere('employees.name_latin', 'ilike', '%' . $query . '%')
                        ->orWhere('departments.code', 'ilike', '%' . $query . '%');
                }
            })
            ->select([
                'employees.id as employee_id',
                'employees.name_kh as employee_name_kh',
                'employees.name_latin as employee_name_latin',
                'employees.id_card as id_card',
                'genders.code as gender_code',
                'departments.code as department_code'
            ])
            ->orderBy('employee_name_kh', 'asc')
            ->get();
        return array('status' => true, 'code' => 200, 'data' => $employees);
    }
}