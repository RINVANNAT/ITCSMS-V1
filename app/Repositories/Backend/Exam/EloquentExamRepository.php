<?php

namespace App\Repositories\Backend\Exam;


use App\Exceptions\GeneralException;
use App\Models\Exam;
use Carbon\Carbon;
use DB;

/**
 * Class EloquentExamRepository
 * @package App\Repositories\Backend\Exam
 */
class EloquentExamRepository implements ExamRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Exam::find($id))) {
            return Exam::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.exams.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getExamsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Exam::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllExams($order_by = 'sort', $sort = 'asc')
    {
        return Exam::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getEntranceExams($order_by = 'sort', $sort = 'asc')
    {
        return Exam::where()->orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getFinalExams($order_by = 'sort', $sort = 'asc')
    {
        return Exam::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Exam::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.general.already_exists'));
        }

        $date_start_end = explode(" - ",$input['date_start_end']);

        $date_start = $date_start_end[0];
        $date_end = $date_start_end[1];

        $exam = new Exam();

        $exam->name = $input['name'];
        $exam->date_start = $date_start;
        $exam->date_end = $date_end;
        $exam->active = isset($input['active'])?true:false;
        $exam->description = $input['description'];
        $exam->academic_year_id = $input['academic_year_id'];
        $exam->type_id = $input['type_id'];

        $exam->created_at = Carbon::now();
        $exam->create_uid = auth()->id();

        if ($exam->save()) {
            return $exam->id;
        }

        throw new GeneralException(trans('exceptions.backend.general.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $exam = $this->findOrThrowException($id);

        $date_start_end = explode(" - ",$input['date_start_end']);

        $date_start = $date_start_end[0];
        $date_end = $date_start_end[1];

        $exam->name = $input['name'];
        $exam->date_start = $date_start;
        $exam->date_end = $date_end;
        $exam->active = isset($input['active'])?true:false;
        $exam->description = $input['description'];
        $exam->academic_year_id = $input['academic_year_id'];
        $exam->type_id = $input['type_id'];

        $exam->created_at = Carbon::now();
        $exam->create_uid = auth()->id();

        if ($exam->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.update_error'));
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {

        $exam = $this->findOrThrowException($id);

        //Don't delete the role is there are users associated
        if ($exam->candidates()->count() > 0) {
            throw new GeneralException(trans('exceptions.backend.exams.has_candidate'));
        }

        $exam->rooms()->sync([]);
        $exam->employees()->sync([]);
        $exam->students()->sync([]);

        if ($exam->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }

    public function requestInputScoreForm ($exam_id, $request) {

        $test = DB::table('candidates')
                ->join('candidate_entrance_exam_course', 'candidates.id', '=', 'candidate_entrance_exam_course.candidate_id')
                ->join('entranceExamCourses', 'candidate_entrance_exam_course.entrance_course_id', '=', 'entranceExamCourses.id')
                ->where([
                    ['candidates.room_id', '=', $request->room_id],
                    ['candidates.exam_id', '=', $exam_id],
                    ['entranceExamCourses.id', '=', $request->entrance_course_id]
                ])
                ->select('candidates.name_kh', 'candidates.id as candidate_id')->get();

        dd($test);
        return json_encode($test);
    }


}
































