<?php  namespace App\Repositories\Backend\CourseSession;


use App\Exceptions\GeneralException;
use App\Models\CourseAnnualClass;
use App\Models\CourseSession;
use App\Models\UserLog;
use Carbon\Carbon;

/**
 * Class EloquentCourseSessionRepository
 * @package App\Repositories\Backend\CourseSession
 */
class EloquentCourseSessionRepository implements CourseSessionRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(CourseSession::find($id))) {
            return CourseSession::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getCourseSessionsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return CourseSession::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllCourseSessions($order_by = 'sort', $sort = 'asc')
    {
        return CourseSession::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {

        $input['create_uid'] = auth()->id();
        $input['created_at'] = Carbon::now();

        if ($courseSession = CourseSession::create($input)) {

            UserLog::log([
                'data' => $courseSession,
                'model' => 'CourseSession',
                'action' => 'Create'
            ]);
            return $courseSession;
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
        $courseSession = $this->findOrThrowException($id);

        $input['updated_at'] = Carbon::now();
        $input['write_uid'] = auth()->id();

        if ($courseSession->update($input)) {
            UserLog::log([
                'data' => $courseSession,
                'model' => 'CourseSession',
                'action' => 'Update'
            ]);
            return $courseSession;
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

        $status = true;
        $model = $this->findOrThrowException($id);
        $courseAnnualClasses = CourseAnnualClass::where("course_session_id",$model->id)->get();

        foreach($courseAnnualClasses as $class){
            if($class->delete()){
                $status = true;
            } else {
                $status = false;
            }
        }

        if($status && $model->delete()){

            UserLog::log([
                'data' => $courseAnnualClasses,
                'model' => 'CourseAnnualClass',
                'action' => 'Destroy'
            ]);

            UserLog::log([
                'data' => $model,
                'model' => 'CourseSession',
                'action' => 'Destroy'
            ]);

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
