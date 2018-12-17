<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Course;
use App\Models\Schedule\Timetable\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Trait ExportCourseToSlotTrait
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait ExportCourseToSlotTrait
{
    public function export_course_program(Request $request)
    {
        $this->validate($request, [
            'department_id' => 'required',
            'degree_id' => 'required',
            'grade_id' => 'required',
            'semester_id' => 'required'
        ]);
        $data = $request->all();
        try {
            $course_programs = Course::where([
                ['department_id', $data['department_id']],
                ['degree_id', $data['degree_id']],
                ['department_option_id', isset($data['option_id']) ? ($data['option_id'] == '' ? null : $data['option_id']) : null],
                ['grade_id', $data['grade_id']],
                ['semester_id', $data['semester_id']],
                ['active', true]
            ])->get();

            $amountCourseProgramImported = 0;

            if (count($course_programs) > 0) {
                foreach ($course_programs as $course_program) {
                    $slots = Slot::where([
                        ['course_program_id', $course_program->id],
                        ['academic_year_id', $data['academic_year_id']],
                        ['semester_id', $data['semester_id']]
                    ])->get();
                    if (count($slots) == 0) {
                        if ($course_program->time_tp > 0) {
                            DB::transaction(function () use ($data, $course_program, &$amountCourseProgramImported) {
                                $newSlot = new Slot();
                                $newSlot->time_tp = $course_program->time_tp;
                                $newSlot->time_td = 0;
                                $newSlot->time_course = 0;
                                $newSlot->academic_year_id = $data['academic_year_id'];
                                $newSlot->course_program_id = $course_program->id;
                                $newSlot->semester_id = $data['semester_id'];
                                $newSlot->created_uid = auth()->user()->id;
                                $newSlot->write_uid = auth()->user()->id;
                                $newSlot->save();
                                $amountCourseProgramImported++;
                            });
                        }
                        if ($course_program->time_td > 0) {
                            DB::transaction(function () use ($data, $course_program, &$amountCourseProgramImported) {
                                $newSlot = new Slot();
                                $newSlot->time_tp = 0;
                                $newSlot->time_td = $course_program->time_td;
                                $newSlot->time_course = 0;
                                $newSlot->academic_year_id = $data['academic_year_id'];
                                $newSlot->course_program_id = $course_program->id;
                                $newSlot->semester_id = $data['semester_id'];
                                $newSlot->created_uid = auth()->user()->id;
                                $newSlot->write_uid = auth()->user()->id;
                                $newSlot->save();
                                $amountCourseProgramImported++;
                            });
                        }
                        if ($course_program->time_course > 0) {
                            DB::transaction(function () use ($data, $course_program, &$amountCourseProgramImported) {
                                $newSlot = new Slot();
                                $newSlot->time_tp = 0;
                                $newSlot->time_td = 0;
                                $newSlot->time_course = $course_program->time_course;
                                $newSlot->academic_year_id = $data['academic_year_id'];
                                $newSlot->course_program_id = $course_program->id;
                                $newSlot->semester_id = $data['semester_id'];
                                $newSlot->created_uid = auth()->user()->id;
                                $newSlot->write_uid = auth()->user()->id;
                                $newSlot->save();
                                $amountCourseProgramImported++;
                            });
                        }
                    }
                }
                return message_success($amountCourseProgramImported);
            } else {
                return message_error('There are 0 courses are program found.');
            }
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function get_course_programs(Request $request)
    {
        $this->validate($request, [
            'academicYear' => 'required',
            'department' => 'required',
            'degree' => 'required',
            'grade' => 'required',
            'semester' => 'required',
        ]);
        try {
            $academic_year_id = $request->academicYear;
            $department_id = $request->department;
            $degree_id = $request->degree;
            $grade_id = $request->grade;
            $semester_id = $request->semester;
            $option_id = (isset($request->option) && $request->option != '' && $request->option != null) ? $request->option : null;

            $course_program_ids = Course::where([
                'department_id' => $department_id,
                'degree_id' => $degree_id,
                'grade_id' => $grade_id,
                'department_option_id' => $option_id,
                'semester_id' => $semester_id,
            ])->pluck('id');

            $slots = Slot::join('courses', 'courses.id', '=', 'slots.course_program_id')
                ->whereIn('course_program_id', $course_program_ids)
                ->where('slots.academic_year_id', $academic_year_id)
                ->with('groups')
                ->select(
                    'slots.id as id',
                    'slots.course_program_id as course_program_id',
                    'slots.time_tp as tp',
                    'slots.time_td as td',
                    'slots.time_course as tc',
                    'courses.name_en as course_name'
                )
                ->orderBy('courses.name_en', 'asc')
                ->get();
            return array('status' => true, 'data' => $slots, 'code' => 200);
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function search_course_program()
    {
        $academic_year_id = request('academic');
        $department_id = request('department');
        $degree_id = request('degree');
        $grade_id = request('grade');
        $semester_id = request('semester');
        $option_id = (request('option') == null ? null : (request('option') == '' ? null : request('option')));

        $course_program_ids = Course::where([
            ['department_id', $department_id],
            ['degree_id', $degree_id],
            ['grade_id', $grade_id],
            ['department_option_id', $option_id],
            ['semester_id', $semester_id],
        ])->pluck('id');

        $slots = Slot::join('courses', 'courses.id', '=', 'slots.course_program_id')
            ->whereIn('course_program_id', $course_program_ids)
            ->where('slots.academic_year_id', $academic_year_id)
            ->where(function ($query) {
                $query->whereRaw('LOWER(courses.name_en) LIKE ?', array('%' . strtolower(request('query')) . '%'))
                    ->orWhereRaw('LOWER(courses.name_kh) LIKE ?', array('%' . strtolower(request('query')) . '%'));
            })
            ->with('groups')
            ->select(
                'slots.id as id',
                'slots.course_program_id as course_program_id',
                'slots.time_tp as tp',
                'slots.time_td as td',
                'slots.time_course as tc',
                'courses.name_en as course_name'
            )
            ->orderBy('courses.name_en', 'asc')
            ->get();
        return array('status' => true, 'course_sessions' => $slots, 'code' => 200);
    }

    public function assign_lecturer_to_course_program()
    {
        $result = [
            'code' => 200,
            'data' => [],
            'message' => "The operation was executed successfully"
        ];

        $slot_id = request('slot_id');
        $lecturer_id = request('lecturer_id');
        if (isset($slot_id) && !is_null($slot_id)) {
            try {
                DB::transaction(function () use ($slot_id, $lecturer_id) {
                    $slot = Slot::find($slot_id);
                    $slot->lecturer_id = $lecturer_id;
                    $slot->write_uid = auth()->user()->id;
                    $slot->updated_at = Carbon::now();
                    $slot->update();
                });
            } catch (\Exception $e) {
                $result['code'] = $e->getCode();
                $result['message'] = $e->getMessage();
            }
        }

        return $result;
    }
}