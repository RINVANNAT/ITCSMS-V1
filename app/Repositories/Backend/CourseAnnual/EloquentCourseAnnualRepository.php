<?php

namespace App\Repositories\Backend\CourseAnnual;


use App\Exceptions\GeneralException;
use App\Models\CourseAnnual;
use App\Models\UserLog;
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
     * @param $input
     * @return CourseAnnual
     * @throws GeneralException
     */
    public function create($input)
    {
        $courseAnnual = new CourseAnnual();

        if(!isset($input['employee_id']) || $input['employee_id'] == '') {
            $input['employee_id'] = null;
        }

        if(isset($input["responsible_department_id"]) && $input["responsible_department_id"] != ''){
            $courseAnnual->responsible_department_id = $input["responsible_department_id"];
        }

        if(isset($input["is_counted_absence"])){
            $courseAnnual->is_counted_absence = true;
        } else {
            $courseAnnual->is_counted_absence = false;
        }

        if(isset($input["is_counted_creditability"])){
            $courseAnnual->is_counted_creditability = true;
        } else {
            $courseAnnual->is_counted_creditability = false;
        }


        if(isset($input["is_having_resitted"])){
            $courseAnnual->is_having_resitted = true;
        } else {
            $courseAnnual->is_having_resitted = false;
        }

        $courseAnnual->course_id = $input['course_id'];
        $courseAnnual->employee_id = isset($input['employee_id'])?$input['employee_id']:null;

        $courseAnnual->academic_year_id = $input['academic_year_id'];
        $courseAnnual->semester_id = $input['semester_id'];
        $courseAnnual->grade_id = $input['grade_id'];
        $courseAnnual->degree_id = $input['degree_id'];
        $courseAnnual->department_id = $input['department_id'];
        $courseAnnual->active = isset($input['active'])?true:false;

        $courseAnnual->time_course = isset($input['time_course'])?$input['time_course']:0;
        $courseAnnual->time_td = isset($input['time_td'])?$input['time_td']:0;
        $courseAnnual->time_tp = isset($input['time_tp'])?$input['time_tp']:0;
//        $courseAnnual->group = isset($input['group'])?$input['group']:null;
        $courseAnnual->credit = isset($input['credit'])?$input['credit']:null;
        $courseAnnual->competency_type_id = isset($input['competency_type_id'])?$input['competency_type_id']:null;
        $courseAnnual->normal_scoring = isset($input['normal_scoring']) and $input['normal_scoring']=="checked"?true:false;
        if(!isset($input['department_option_id']) || $input['department_option_id'] == ""){
            $input['department_option_id'] = null;
        }
        $courseAnnual->department_option_id = $input['department_option_id'];

        if(isset($input['reference_course_id']) && $input['reference_course_id'] != '' &&  is_numeric($input['reference_course_id'])) {
            $courseAnnual->reference_course_id = $input['reference_course_id'];
        } else{
            $courseAnnual->reference_course_id = null;
        }



        $courseAnnual->name_kh = isset($input['name_kh'])?$input['name_kh']:null;
        $courseAnnual->name_en = isset($input['name_en'])?$input['name_en']:null;
        $courseAnnual->name_fr = isset($input['name_fr'])?$input['name_fr']:null;

//        $courseAnnual->score_percentage_column_1 = isset($input['score_percentage_column_1'])?$input['score_percentage_column_1']:10;
//        $courseAnnual->score_percentage_column_2 = isset($input['score_percentage_column_2'])?$input['score_percentage_column_2']:30;
//        $courseAnnual->score_percentage_column_3 = isset($input['score_percentage_column_3'])?$input['score_percentage_column_3']:60;
        $courseAnnual->created_at = Carbon::now();
        $courseAnnual->create_uid = auth()->id();

        if ($courseAnnual->save()) {

            $storeData = json_encode($courseAnnual);
            UserLog::log([
                'model' => 'CourseAnnual',
                'action'   => 'Create', // Import, Create, Delete, Update
                'data'     => $storeData // if it is create action, store only the new id.
            ]);
            return $courseAnnual;
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

        if(!isset($input['department_option_id']) || $input['department_option_id'] == ""){
            $courseAnnual->department_option_id = null;
        } else {
            $courseAnnual->department_option_id = $input['department_option_id'];
        }

        if(!isset($input['employee_id']) || $input['employee_id'] == '') {
            $courseAnnual->employee_id = null;
        } else {
            $courseAnnual->employee_id = $input['employee_id'];
        }

        if(isset($input["responsible_department_id"]) && $input["responsible_department_id"] != ''){
            $courseAnnual->responsible_department_id = $input["responsible_department_id"];
        } else {
            // This is temporary, later if they don't pass along, we do nothing
            // We must removed disabled attribute in responsible department
            $courseAnnual->responsible_department_id = null;
        }

        if(isset($input["course_id"]) && $input["course_id"] != '') {
            $courseAnnual->course_id = $input['course_id'];
        }
        if(isset($input["semester_id"]) && $input["semester_id"] != '') {
            $courseAnnual->semester_id = $input['semester_id'];
        }
        if(isset($input["active"]) && $input["active"] != '') {
            $courseAnnual->active = $input['active'];
        }

        if(isset($input["department_id"]) && $input["department_id"] != '') {
            $courseAnnual->department_id = $input['department_id'];
        }
        if(isset($input["department_option_id"]) && $input["department_option_id"] != '') {
            $courseAnnual->department_option_id = $input['department_option_id'];
        } else {
            $courseAnnual->department_option_id = null;
        }

        if(isset($input["time_course"]) && $input["time_course"] != '') {
            $courseAnnual->time_course = $input['time_course'];
        }
        if(isset($input["time_td"]) && $input["time_td"] != '') {
            $courseAnnual->time_td = $input['time_td'];
        }
        if(isset($input["time_tp"]) && $input["time_tp"] != '') {
            $courseAnnual->time_tp = $input['time_tp'];
        }
        if(isset($input["name_kh"]) && $input["name_kh"] != '') {
            $courseAnnual->name_kh = $input['name_kh'];
        }
        if(isset($input["name_en"]) && $input["name_en"] != '') {
            $courseAnnual->name_en = $input['name_en'];
        }
        if(isset($input["name_fr"]) && $input["name_fr"] != '') {
            $courseAnnual->name_fr = $input['name_fr'];
        }
        if(isset($input["credit"]) && $input["credit"] != '') {
            $courseAnnual->credit = $input['credit'];
        }
        if(isset($input["competency_type_id"]) && $input["competency_type_id"] != '') {
            $courseAnnual->competency_type_id = $input['competency_type_id'];
        }

        if(isset($input["normal_scoring"]) && $input["normal_scoring"] == "checked") {
            $courseAnnual->normal_scoring = true;
        } else {
            $courseAnnual->normal_scoring = false;
        }

        if(isset($input['reference_course_id']) && $input['reference_course_id'] != '' &&  is_numeric($input['reference_course_id'])) {
            $courseAnnual->reference_course_id = $input['reference_course_id'];
        } else{
            $courseAnnual->reference_course_id = null;
        }

        if(isset($input["is_counted_absence"])){
            $courseAnnual->is_counted_absence = true;
        } else {
            $courseAnnual->is_counted_absence = false;
        }

        if(isset($input["is_having_resitted"])){
            $courseAnnual->is_having_resitted = true;
        } else {
            $courseAnnual->is_having_resitted = false;
        }

        if(isset($input["is_counted_creditability"])){
            $courseAnnual->is_counted_creditability = true;
        } else {
            $courseAnnual->is_counted_creditability = false;
        }

        $courseAnnual->updated_at = Carbon::now();
        $courseAnnual->write_uid = auth()->id();

        if ($courseAnnual->save()) {

            $storeData = json_encode($courseAnnual);
            UserLog::log([
                'model' => 'CourseAnnual',
                'action'   => 'Update', // Import, Create, Delete, Update
                'data'     => $storeData // if it is create action, store only the new id.
            ]);
            return $courseAnnual;
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

        foreach($model->courseAnnualClass as $object){
            $object->delete();
        }

        if ($model->delete()) {

            $storeData = json_encode($model);
            UserLog::log([
                'model' => 'CourseAnnual',
                'action'   => 'Destroy', // Import, Create, Delete, Update
                'data'     => $storeData // if it is create action, store only the new id.
            ]);
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
