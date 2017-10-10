<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableSlotRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

/**
 * Class ViewTimetableByTeacherController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait ViewTimetableByTeacherController
{
    /**
     * @var EloquentTimetableSlotRepository
     */
    protected $viewTimetableSlotRepoByTeacher;

    /**
     * ViewTimetableByTeacherController constructor.
     * @param EloquentTimetableSlotRepository $eloquentTimetableSlotRepository
     */
    public function __construct(EloquentTimetableSlotRepository $eloquentTimetableSlotRepository)
    {
        $this->viewTimetableSlotRepoByTeacher = $eloquentTimetableSlotRepository;
    }

    /**
     * @return mixed
     */
    public function get_teacher_timetable()
    {
        $newTimetableSlots = DB::table('timetables')
            ->join('timetable_slots', 'timetables.id', '=', 'timetable_slots.timetable_id')
            ->join('departments', 'departments.id', '=', 'timetables.department_id')
            ->join('degrees', 'degrees.id', '=', 'timetables.degree_id')
            ->join('grades', 'grades.id', '=', 'timetables.grade_id')
            ->join('groups', 'groups.id', '=', 'timetables.group_id')
            ->leftJoin('rooms', 'rooms.id', '=', 'timetable_slots.room_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->where([
                ['timetables.academic_year_id', \request('academic_year')],
                ['timetables.week_id', \request('week')],
                ['timetable_slots.teacher_name', (auth()->user()->employees)[0]->name_latin],
            ])
            ->select(
                'timetable_slots.id',
                'timetable_slots.course_name as title',
                'timetable_slots.course_name',
                'timetable_slots.teacher_name',
                'timetable_slots.group_merge_id as group_merge_id',
                'timetable_slots.type as course_type',
                'timetable_slots.start',
                'timetable_slots.end',
                'buildings.code as building',
                'rooms.name as room',
                'departments.code as department_name',
                'degrees.code as degree_name',
                'grades.code as grade_name',
                'groups.code as group_name'
            )->get();

        /*$test = new Collection();  // timetableSlots.

        $newTimetableSlots = collect($newTimetableSlots)->groupBy('group_merge_id')->toArray();

        foreach ($newTimetableSlots as $index => $item) {
            $item = collect($item)->toArray();
            $groups = new Collection();
            foreach ($item as $subItem) {
                $groups->push($subItem->group_name);
            }
            array_push($item, $groups);
            $test->push($item);
        }*/

        return Response::json(['status' => true, 'timetableSlots' => $newTimetableSlots]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function move_timetable_slot_teacher(Request $request)
    {
        if (isset($request->timetable_slot_id)) {

            // find timetable slot
            $timetable_slot = TimetableSlot::find($request->timetable_slot_id);
            // update timetable slot
            if ($timetable_slot instanceof TimetableSlot) {
                $start = new Carbon($request->start_date);
                $end = $start->addHours($timetable_slot->durations);
                $timetable_slot->start = new Carbon($request->start_date);
                $timetable_slot->end = $end;
                $timetable_slot->updated_at = Carbon::now();

                // update merge timetable slot
                if ($timetable_slot->update()) {
                    $this->viewTimetableSlotRepoByTeacher->update_merge_timetable_slot($timetable_slot);
                }
                return Response::json(['status' => true]);
            }
        }
        return Response::json(['status' => false]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function resize_timetable_slot_teacher(Request $request)
    {
        if (isset($request->timetable_slot_id)) {
            // find timetable slot
            $timetable_slot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetable_slot instanceof TimetableSlot) {
                // get old durations.
                $old_durations = $timetable_slot->durations;
                // find new durations.
                $new_durations = $this->viewTimetableSlotRepoByTeacher->durations(new Carbon($timetable_slot->start), new Carbon($request->end));
                // set update new value.
                $timetable_slot->durations = $new_durations;
                $timetable_slot->end = new Carbon($request->end);
                $timetable_slot->updated_at = Carbon::now();
                // find course session.
                $slot = Slot::find($timetable_slot->slot_id);

                // calculate duration time.
                if ($new_durations > $old_durations) {
                    $interval = $new_durations - $old_durations;
                    $slot->time_remaining = $slot->time_remaining - $interval;
                } else {
                    $interval = $old_durations - $new_durations;
                    $slot->time_remaining = $slot->time_remaining + $interval;
                }

                // validate duration time.
                if (($slot->time_remaining <= $slot->time_used) && $slot->time_remaining >= 0) {
                    $slot->update();
                    // update timetable slot.
                    $timetable_slot->update();
                    // update merge timetable slot.
                    $this->viewTimetableSlotRepoByTeacher->update_merge_timetable_slot($timetable_slot);
                    return Response::json(['status' => true, 'timetable_slot' => $timetable_slot]);
                } else {
                    return Response::json(['status' => false, 'message' => 'Time is limited.']);
                }
            }
        }
        return Response::json(['status' => false, 'message' => 'The timetable slot did not create yet.']);
    }
}