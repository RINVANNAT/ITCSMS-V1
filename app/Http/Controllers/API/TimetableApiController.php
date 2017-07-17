<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentAnnual;
use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableSlotRepository;
use Illuminate\Support\Collection;

/**
 * Class TimetableApiController
 * @package App\Http\Controllers\API
 */
class TimetableApiController extends Controller
{
    /**
     * @var EloquentTimetableSlotRepository
     */
    protected $timetableSlotRepo;

    /**
     * TimetableApiController constructor.
     * @param EloquentTimetableSlotRepository $eloquentTimetableSlotRepository
     */
    public function __construct(EloquentTimetableSlotRepository $eloquentTimetableSlotRepository)
    {
        $this->timetableSlotRepo = $eloquentTimetableSlotRepository;
    }

    /**
     * Show timetable slot.
     *
     * @param $academic_year_id
     * @param $student_id_card
     * @param $semester_id
     * @param $week_id
     * @return Collection|null
     */
    public function show($academic_year_id, $student_id_card, $semester_id, $week_id)
    {
        // get student id.
        $student = Student::where([
            'id_card' => $student_id_card,
        ])->first();

        // get student id_card
        $studentAnnual = StudentAnnual::where([
            'student_id' => $student->id,
            'academic_year_id' => $academic_year_id
        ])->first();

        // find group_id
        if ($studentAnnual->group != '') {
            $group_id = Group::where('code', $studentAnnual->group)->first()->id;
        } else {
            $group_id = null;
        }

        // find timetable.
        $timetable = Timetable::where([
            'academic_year_id' => $academic_year_id,
            'department_id' => $studentAnnual->department_id,
            'degree_id' => $studentAnnual->degree_id,
            'grade_id' => $studentAnnual->grade_id,
            'option_id' => $studentAnnual->department_option_id,
            'group_id' => $group_id,
            'semester_id' => $semester_id,
            'week_id' => $week_id
        ])->first();

        // find all timetable slots
        $timetable_slots = TimetableSlot::where('timetable_id', $timetable->id)
            ->leftJoin('rooms', 'rooms.id', '=', 'timetable_slots.room_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->select(
                'timetable_slots.id',
                'timetable_slots.course_name as title',
                'timetable_slots.course_name',
                'timetable_slots.teacher_name',
                'timetable_slots.type as type',
                'timetable_slots.start',
                'timetable_slots.end',
                'buildings.code as building',
                'rooms.name as room'
            )
            ->get();
        $timetableSlots = new Collection();

        // get timetable slot from language section.
        if ($timetable->department_id < 12 && ($timetable instanceof Timetable)) {
            // get student annuals id
            $student_annual_ids = $this->timetableSlotRepo->find_student_annual_ids($timetable);
            $department_languages = array(12, 13); // (english, french)
            foreach ($department_languages as $department_language) {
                // get group language, [@return array(Collection $groups, Array $groups)]
                $groups = $this->timetableSlotRepo->get_group_student_annual_form_language($department_language, $student_annual_ids, $timetable);

                // get timetable language,
                $timetables = $this->timetableSlotRepo->get_timetables_form_language_by_student_annual($groups[0], $timetable, $department_language);

                // get timetable slots [@return array(timetableSlots, groupsRoom)]
                $timetableSlotsLang = $this->timetableSlotRepo->get_timetable_slot_language_dept($timetables, $groups[0]);

                // set timetable slots language to view.
                $this->timetableSlotRepo->set_timetable_slot_language($timetableSlots, $timetableSlotsLang[1], $timetableSlotsLang[0]);
            }
        }
        // get all timetable slots.
        foreach ($timetable_slots as $timetable_slot) {
            if (($timetable_slot instanceof TimetableSlot) && is_object($timetable_slot)) {

                $newTimetableSlot = TimetableSlot::find($timetable_slot->id);
                $timetableSlot = new Collection($newTimetableSlot);
                $timetableSlot->put('building', $timetable_slot->building);
                $timetableSlot->put('room', $timetable_slot->room);
                $timetableSlots->push($timetableSlot);
            }
        }

        if (count($timetableSlots)) {
            return $timetableSlots;
        }
        return null;
    }

    /**
     * Get all semesters.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get_semesters()
    {
        return Semester::all();
    }

    /**
     * Get all weeks.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get_weeks()
    {
        return Week::all();
    }
}
