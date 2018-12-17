<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Configuration;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Trait TimetableAssignmentTrait
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait TimetableAssignmentTrait
{
    public function get_timetable_assignment()
    {
        $timetable_assignments = Configuration::where('key', 'like', 'timetable_%')
            ->select('id', 'value', 'key', 'description', 'created_at', 'updated_at')
            ->get();

        $departments = new Collection();
        if (count($timetable_assignments) > 0) {
            foreach ($timetable_assignments as $assignment) {
                $department = new Collection(Department::where('id', $assignment->value)->select('code')->first());
                $department->put('start', (new Carbon($assignment->created_at))->toDateString());
                $department->put('end', (new Carbon($assignment->updated_at))->toDateString());
                $department->put('description', $assignment->description);
                $department->put('key_id', $assignment->id);
                $departments->push($department);
            }
            return ['status' => true, 'departments' => $departments];
        }
        return ['status' => false];
    }

    public function assign_turn_create_timetable()
    {
        $result = [
            'status' => false,
            'message' => null
        ];

        if (count(request('departments')) > 0) {
            foreach (request('departments') as $item) {
                if (Configuration::where('key', 'timetable_' . $item)->first() instanceof Configuration) {
                    return ['status' => $result, 'message' => 'The key: timetable_' . $item . ' value already existed'];
                }
            }

            foreach (request('departments') as $item) {
                $newAssignCreateTimetable = new Configuration();
                $newAssignCreateTimetable->key = 'timetable_' . $item;
                $newAssignCreateTimetable->value = $item;
                $newAssignCreateTimetable->created_at = new Carbon(request('start'));
                $newAssignCreateTimetable->updated_at = new Carbon(request('end'));
                $newAssignCreateTimetable->description = 'true';
                $newAssignCreateTimetable->create_uid = auth()->user()->id;
                $newAssignCreateTimetable->write_uid = auth()->user()->id;
                if ($newAssignCreateTimetable->save()) {
                    $result['status'] = true;
                } else {
                    $result['status'] = false;
                    break;
                }
            }
            // set_permission_create_timetable
            if ($result) {
                $result['message'] = 'All those department are assigned.';
            }
        } else {
            $result['message'] = 'Something went wrong.';
        }
        return $result;
    }

    public function assign_delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $configuration = Configuration::find($request->id);
        if ($configuration instanceof Configuration) {
            $configuration->delete();
            return ['status' => true];
        }
        return ['status' => false];
    }

    public function assign_update()
    {
        $now = Carbon::now('Asia/Phnom_Penh');
        $configuration = Configuration::find(request('configuration_id'));
        if ($configuration instanceof Configuration) {
            $configuration->created_at = new Carbon(request('start'));
            $configuration->updated_at = new Carbon(request('end'));

            if ((strtotime($now) >= strtotime(new Carbon(request('start')))) && (strtotime($now) <= strtotime(new Carbon(request('end'))))) {
                $configuration->description = 'true';
                $configuration->timestamps = false;

            } else if (strtotime($now) > strtotime(new Carbon(request('end')))) {
                $configuration->description = 'finished';
                $configuration->timestamps = false;
            } else {
                $configuration->description = 'false';
                $configuration->timestamps = false;
            }
            $configuration->update();
            return ['status' => true];
        }
        return ['status' => false];
    }

    public function update_assign_timetable()
    {
        $configuration = Configuration::find(request('id'));
        return [
            'status' => true,
            'start' => (new Carbon($configuration->created_at))->toDateString(),
            'end' => (new Carbon($configuration->updated_at))->toDateString()
        ];
    }
}