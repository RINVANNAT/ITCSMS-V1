<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
use Arcanedev\Support\Collection;

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
            ->whereNotNull('room_id')
            ->select('room_id as room', 'start', 'end')
            ->get();

        $tmps = (new Collection($timetableSlots))->groupBy('start', 'end')->toArray();

        $rooms = [];
        foreach ($tmps as $tmp) {
            $sub_rooms = [];
            $sub_rooms['title'] = [];
            $sub_rooms['rooms'] = [];

            foreach ($tmp as $key => $item) {
                $sub_rooms['start'] = $item['start'];
                $sub_rooms['end'] = $item['end'];
                array_push($sub_rooms['rooms'], $item['room']);
            }
            
            array_push($sub_rooms['title'], $sub_rooms['rooms']);
            array_push($rooms, $sub_rooms);
        }

        return ['status' => true, 'data' => $rooms];
    }
}
