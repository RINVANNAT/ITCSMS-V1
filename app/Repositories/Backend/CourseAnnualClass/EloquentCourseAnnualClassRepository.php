<?php

namespace App\Repositories\Backend\CourseAnnualClass;


use App\Exceptions\GeneralException;
use App\Models\CourseAnnualClass;
use Carbon\Carbon;
use App\Models\UserLog;

/**
 * Class EloquentCourseAnnualRepository
 * @package App\Repositories\Backend\CourseAnnual
 */
class EloquentCourseAnnualClassRepository implements CourseAnnualClassRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(CourseAnnualClass::find($id))) {
            return CourseAnnualClass::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getCourseAnnualClassesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return CourseAnnualClass::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllCourseAnnualClasses($order_by = 'sort', $sort = 'asc')
    {
        return CourseAnnualClass::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        $test = [];
        if(!isset($input['department_option_id']) || $input['department_option_id'] == ""){
            $input['department_option_id'] = null;
        }
        if(isset($input['groups']) && count($input['groups']) > 0){ //---array of group_id selected for one specific course_annual

            // For now, only group has many records
            foreach($input['groups'] as $group){
                $courseAnnualClass = new CourseAnnualClass();

                if(isset($input['course_annual_id'])){
                    $courseAnnualClass->course_annual_id = $input['course_annual_id'];
                }
                if(isset($input['course_session_id'])){
                    $courseAnnualClass->course_session_id = $input['course_session_id'];
                }
                // $courseAnnualClass->group = $group;
                $courseAnnualClass->group_id = $group;
                $courseAnnualClass->created_at = Carbon::now();
                $courseAnnualClass->create_uid = auth()->id();
                if(!$courseAnnualClass->save()){
                    return false;
                }

                $storeData = json_encode($courseAnnualClass);
                UserLog::log([
                    'model' => 'CourseAnnualClass',
                    'action'   => 'Create', // Import, Create, Delete, Update
                    'data'     => $storeData // if it is create action, store only the new id.
                ]);
            }
        } else { // if group is not passed, store as well. Just without group
            $courseAnnualClass = new CourseAnnualClass();

            if(isset($input['course_annual_id'])){
                $courseAnnualClass->course_annual_id = $input['course_annual_id'];
            }

            if(isset($input['course_session_id'])){
                $courseAnnualClass->course_session_id = $input['course_session_id'];
            }

            $courseAnnualClass->created_at = Carbon::now();
            $courseAnnualClass->create_uid = auth()->id();

            if(!$courseAnnualClass->save()){
                return false;
            }

            $storeData = json_encode($courseAnnualClass);
            UserLog::log([
                'model' => 'CourseAnnualClass',
                'action'   => 'Create', // Import, Create, Delete, Update
                'data'     => $storeData // if it is create action, store only the new id.
            ]);
        }


        return true;
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {

        $courseAnnualClass = $this->findOrThrowException($id);

        if(!isset($input['department_option_id']) || $input['department_option_id'] == ""){
            $input['department_option_id'] = $courseAnnualClass->department_option_id;
        }
        $courseAnnualClass->course_annual_id = isset($input['course_annual_id'])?$input['course_annual_id']:$courseAnnualClass->course_annual_id;
        $courseAnnualClass->course_session_id = isset($input['course_session_id'])?$input['course_session_id']:$courseAnnualClass->course_session_id;
        $courseAnnualClass->grade_id = isset($input['grade_id'])?$input['grade_id']:$courseAnnualClass->grade_id;
        $courseAnnualClass->degree_id = isset($input['degree_id'])?$input['degree_id']:$courseAnnualClass->degree_id;
        $courseAnnualClass->department_id = isset($input['department_id'])?$input['department_id']:$courseAnnualClass->department_id;
        $courseAnnualClass->group = isset($input['group'])?$input['group']:$courseAnnualClass->group;
        $courseAnnualClass->department_option_id = $input['department_option_id'];
        $courseAnnualClass->updated_at = Carbon::now();
        $courseAnnualClass->write_uid = auth()->id();

        if ($courseAnnualClass->save()) {

            $storeData = json_encode($courseAnnualClass);
            UserLog::log([
                'model' => 'CourseAnnualClass',
                'action'   => 'Update', // Import, Create, Delete, Update
                'data'     => $storeData // if it is create action, store only the new id.
            ]);
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

        $model = $this->findOrThrowException($id);

        if ($model->delete()) {

            $storeData = json_encode($model);
            UserLog::log([
                'model' => 'CourseAnnualClass',
                'action'   => 'Destroy', // Import, Create, Delete, Update
                'data'     => $storeData // if it is create action, store only the new id.
            ]);
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
