<?php

namespace App\Repositories\Backend\CourseAnnual;


use App\Exceptions\GeneralException;
use App\Models\CourseAnnual;
use Carbon\Carbon;

/**
 * Class EloquentCourseAnnualRepository
 * @package App\Repositories\Backend\CourseAnnual
 */
class EloquentCourseAnnualRepository implements CourseAnnualRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(CourseAnnual::find($id))) {
            return CourseAnnual::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getCourseAnnualsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return CourseAnnual::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllCourseAnnuals($order_by = 'sort', $sort = 'asc')
    {
        return CourseAnnual::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        $courseAnnual = new CourseAnnual();
        $courseAnnual->course_id = $input['course_id'];
        $courseAnnual->employee_id = isset($input['employee_id'])?$input['employee_id']:1;
        $courseAnnual->academic_year_id = $input['academic_year_id'];
        $courseAnnual->semester_id = $input['semester_id'];
        $courseAnnual->grade_id = $input['grade_id'];
        $courseAnnual->degree_id = $input['degree_id'];
        $courseAnnual->department_id = $input['department_id'];
        $courseAnnual->active = isset($input['active'])?true:false;

        $courseAnnual->score_percentage_column_1 = isset($input['score_percentage_column_1'])?$input['score_percentage_column_1']:10;
        $courseAnnual->score_percentage_column_2 = isset($input['score_percentage_column_2'])?$input['score_percentage_column_2']:30;
        $courseAnnual->score_percentage_column_3 = isset($input['score_percentage_column_3'])?$input['score_percentage_column_3']:60;
        $courseAnnual->created_at = Carbon::now();
        $courseAnnual->create_uid = auth()->id();

        if ($courseAnnual->save()) {
            return true;
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
        $courseAnnual = $this->findOrThrowException($id);
        $courseAnnual->course_id = $input['course_id'];
        $courseAnnual->semester_id = $input['semester_id'];
        $courseAnnual->active = isset($input['active'])?true:false;
        $courseAnnual->employee_id = isset($input['employee_id'])?$input['employee_id']:1;
        $courseAnnual->updated_at = Carbon::now();
        $courseAnnual->score_percentage_column_1 = isset($input['score_percentage_column_1'])?$input['score_percentage_column_1']:10;
        $courseAnnual->score_percentage_column_2 = isset($input['score_percentage_column_2'])?$input['score_percentage_column_2']:30;
        $courseAnnual->score_percentage_column_3 = isset($input['score_percentage_column_3'])?$input['score_percentage_column_3']:60;

        $courseAnnual->write_uid = auth()->id();

        if ($courseAnnual->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.update_error'));
    }

    public function update_score_per($id, $input)
    {


//        $courseAnnual = $this->findOrThrowException($id);

        $courseAnnual = CourseAnnual::find($id);
        $courseAnnual->updated_at = Carbon::now();
        $courseAnnual->score_percentage_column_1 = isset($input['score_percentage_column_1'])?$input['score_percentage_column_1']:10;
        $courseAnnual->score_percentage_column_2 = isset($input['score_percentage_column_2'])?$input['score_percentage_column_2']:30;
        $courseAnnual->score_percentage_column_3 = isset($input['score_percentage_column_3'])?$input['score_percentage_column_3']:60;
        $courseAnnual->write_uid = auth()->id();
        $courseAnnual->save();


        return true;
//        throw new GeneralException(trans('exceptions.backend.general.update_error'));
    }



    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {

        $model = $this->findOrThrowException($id);

        if ($model->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
