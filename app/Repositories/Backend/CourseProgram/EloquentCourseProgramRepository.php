<?php  namespace App\Repositories\Backend\CourseProgram;


use App\Exceptions\GeneralException;
use App\Models\Course;
use Carbon\Carbon;

/**
 * Class EloquentCourseProgramRepository
 * @package App\Repositories\Backend\CourseProgram
 */
class EloquentCourseProgramRepository implements CourseProgramRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Course::find($id))) {
            return Course::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getCourseProgramsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Course::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllCoursePrograms($order_by = 'sort', $sort = 'asc')
    {
        return Course::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {

        $courseProgram = new Course();
        if ( array_key_exists("name_kh",$input)){
            $courseProgram->name_kh = $input["name_kh"];
        }
        if ( array_key_exists("name_en",$input)){
            $courseProgram->name_en = $input["name_en"];
        }
        if ( array_key_exists("name_fr",$input)){
            $courseProgram->name_fr = $input["name_fr"];
        }
        if ( array_key_exists("time_course",$input)){
            $courseProgram->time_course = $input["time_course"];
        }
        if ( array_key_exists("time_tp",$input)){
            $courseProgram->time_tp = $input["time_tp"];
        }
        if ( array_key_exists("time_td",$input)){
            $courseProgram->time_td = $input["time_td"];
        }
        if ( array_key_exists("code",$input)){
            $courseProgram->code = $input["code"];
        }
        if ( array_key_exists("credit",$input)){
            $courseProgram->credit = $input["credit"];
        }
        if ( array_key_exists("degree_id",$input)){
            $courseProgram->degree_id = $input["degree_id"];
        }
        if ( array_key_exists("department_id",$input)){
            $courseProgram->department_id = $input["department_id"];
        }
        if ( array_key_exists("semester_id",$input)){
            $courseProgram->semester_id = $input["semester_id"];
        }

        $courseProgram->create_uid = auth()->id();
        $courseProgram->updated_at = Carbon::now();
        $courseProgram->write_uid = auth()->id();



        if ($courseProgram->save()) {
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
        $courseProgram = $this->findOrThrowException($id);


        if ( array_key_exists("name_kh",$input)){
            $courseProgram->name_kh = $input["name_kh"];
        }
        if ( array_key_exists("name_en",$input)){
            $courseProgram->name_en = $input["name_en"];
        }
        if ( array_key_exists("name_fr",$input)){
            $courseProgram->name_fr = $input["name_fr"];
        }
        if ( array_key_exists("time_course",$input)){
            $courseProgram->time_course = $input["time_course"];
        }
        if ( array_key_exists("time_tp",$input)){
            $courseProgram->time_tp = $input["time_tp"];
        }
        if ( array_key_exists("time_td",$input)){
            $courseProgram->time_td = $input["time_td"];
        }
        if ( array_key_exists("code",$input)){
            $courseProgram->code = $input["code"];
        }
        if ( array_key_exists("credit",$input)){
            $courseProgram->credit = $input["credit"];
        }
        if ( array_key_exists("degree_id",$input)){
            $courseProgram->degree_id = $input["degree_id"];
        }
        if ( array_key_exists("department_id",$input)){
            $courseProgram->department_id = $input["department_id"];
        }
        if ( array_key_exists("semester_id",$input)){
            $courseProgram->semester_id = $input["semester_id"];
        }

        $courseProgram->updated_at = Carbon::now();
        $courseProgram->write_uid = auth()->id();




        if ($courseProgram->save()) {
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
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
