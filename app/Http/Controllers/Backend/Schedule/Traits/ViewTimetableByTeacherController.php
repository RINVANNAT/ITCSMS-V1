<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableSlotRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

    public function get_teacher_timetable()
    {
        $self = request('filter_by');
        $timetableSlots = new Collection();

        $timetable = Timetable::where([
            ['academic_year_id', request('academicYears')],
            ['department_id', request('departments')],
            ['degree_id', request('degrees')],
            ['grade_id', request('grades')],
            ['semester_id', request('semesters')],
            ['week_id', request('weeks')],
            ['group_id', request('groups')],
            ['option_id', request('options')]
        ])->first();

        if ($timetable instanceof Timetable) {
            if ($timetable instanceof Timetable) {
                if ($self != null) {
                    $this->viewTimetableSlotRepoByTeacher->get_timetable_slot_with_conflict_info($timetable, $timetableSlots, auth()->user()->name);
                } else {
                    $this->viewTimetableSlotRepoByTeacher->get_timetable_slot_with_conflict_info($timetable, $timetableSlots);
                }
            }

            // get student annuals.
            if (request('departments') < 12 && ($timetable instanceof Timetable)) {
                // get student annuals id
                $student_annual_ids = $this->viewTimetableSlotRepoByTeacher->find_student_annual_ids($timetable);
                $department_languages = array(12, 13); // (english, french)
                foreach ($department_languages as $department_language) {
                    // get group language, [@return array(Collection $groups, Array $groups)]
                    $groups = $this->viewTimetableSlotRepoByTeacher->get_group_student_annual_form_language($department_language, $student_annual_ids, $timetable);

                    // get timetable language,
                    $timetables = $this->viewTimetableSlotRepoByTeacher->get_timetables_form_language_by_student_annual($groups[0], $timetable, $department_language);

                    // get timetable slots [@return array(timetableSlots, groupsRoom)]
                    $timetableSlotsLang = $this->viewTimetableSlotRepoByTeacher->get_timetable_slot_language_dept($timetables, $groups[0]);

                    // set timetable slots language to view.
                    $this->viewTimetableSlotRepoByTeacher->set_timetable_slot_language($timetableSlots, $timetableSlotsLang[1], $timetableSlotsLang[0]);
                }
            }

        }
        return Response::json(['status' => true, 'timetable' => $timetable == null ? null : $timetable, 'timetableSlots' => $timetableSlots]);
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