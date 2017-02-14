<?php  namespace App\Repositories\Backend\CourseProgram;


use App\Exceptions\GeneralException;
use App\Models\Course;
use App\Models\CourseAnnual;
use App\Models\CourseAnnualClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        $input['create_uid'] = auth()->id();
        $input['created_at'] = Carbon::now();

        if (Course::create($input)) {
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

        $input['updated_at'] = Carbon::now();
        $input['write_uid'] = auth()->id();


        if ($courseProgram->update($input)) {
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
        // Delete course annual class first
        $course_annuals = DB::table('course_annuals')->where('course_id',$model->id)->get();
        foreach($course_annuals as $course_annual){
            DB::table("course_annual_classes")->where("course_annual_id",$course_annual->id)->delete();
        }

        // Delete course annual later
        DB::table("course_annuals")->where('course_id',$model->id)->delete();

        // Delete course program
        if ($model->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
