<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
use Arcanedev\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class CheckAvailableRoomController
 * @package App\Http\Controllers\Backend\Schedule
 */
class CheckAvailableRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|mixed
     */
    public function index()
    {
        $academics = AcademicYear::orderBy('id', 'desc')->get();
        $weeks = Week::all();
        return view('backend.schedule.timetables.check-available-room')->with([
            'academics' => $academics,
            'weeks' => $weeks
        ]);
    }

    /**
     * Get all available room.
     *
     * @return array
     */
    public function get_rooms()
    {
        $timetables = Timetable::where([
            ['academic_year_id', \request('academic')],
            ['week_id', \request('week')]
        ])
            ->lists('id');

        $timetableSlots = TimetableSlot::whereIn('timetable_id', $timetables)
            ->join('rooms', 'rooms.id', '=', 'timetable_slots.room_id')
            ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->whereNotNull('room_id')
            ->select('timetable_slots.start as start', 'timetable_slots.end as end', 'timetable_slots.id as id', 'timetable_slots.room_id as room_id')
            ->selectRaw("CONCAT(buildings.code, '-', rooms.name) as room")
            ->distinct('room_id')
            ->get();

        $tmps = (new Collection($timetableSlots))->groupBy('start')->toArray();
        $end = (new Collection($timetableSlots))->groupBy('end')->toArray();



        $rooms = [];
        foreach ($tmps as $tmp) {
            $sub_rooms = [];
            $sub_rooms['title'] = [];
            $sub_rooms['rooms'] = [];

            foreach ($tmp as $key => $item) {
                $sub_rooms['start'] = $item['start'];
                $sub_rooms['end'] = $item['end'];
                $props = [];
                $props['id'] = $item['room_id'];
                $props['room'] = $item['room'];
                $props['timetable_slot_id'] = $item['id'];
                array_push($sub_rooms['rooms'], $props);
            }
            $sub_rooms['title'] = $sub_rooms['rooms'];
            array_push($rooms, $sub_rooms);
        }
        return ['status' => true, 'data' => $rooms];
    }

    /**
     * Get all unavailable room.
     *
     * @return mixed
     */
    public function get_unavailable_room_info()
    {
        $timetableSlot = TimetableSlot::find(request('timetableSlotId'));
        $timetable = $timetableSlot->timetable;

        $timetable_slots = TimetableSlot::find($timetableSlot->id)
            ->join('timetables', 'timetables.id', '=', 'timetable_slots.timetable_id')
            ->join('departments', 'departments.id', '=', 'timetables.department_id')
            ->leftJoin('rooms', 'rooms.id', '=', 'timetable_slots.room_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->where([
                ['timetables.id', $timetable->id],
                ['timetable_slots.id', $timetableSlot->id],
            ])
            ->select(
                'timetable_slots.id',
                'timetable_slots.course_name as title',
                'timetable_slots.course_name',
                'timetable_slots.teacher_name',
                'timetable_slots.type as course_type',
                'timetable_slots.start',
                'timetable_slots.end',
                'buildings.code as building',
                'departments.code as dept_code',
                'rooms.name as room'
            )->first();

        return $timetable_slots;
    }
}
