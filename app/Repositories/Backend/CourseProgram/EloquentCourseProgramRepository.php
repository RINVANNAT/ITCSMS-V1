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

        $course = new Course();

        if(isset($input['responsible_department_id']) && $input['responsible_department_id'] != ""){
            $course->responsible_department_id = $input["responsible_department_id"];
        } else {
            $course->responsible_department_id = null;
        }

        if(isset($input['department_option_id']) && $input['department_option_id'] != ""){
            $course->department_option_id = $input["department_option_id"];
        } else {
            $course->department_option_id = null;
        }

        if(!isset($input['is_counted_creditability'])) {
            $course->is_counted_creditability = false;//---this course program is consider to be studied by student but they dont count it for the student creditability
        }

        if(!isset($input['code']) || $input['code'] == ""){
            $input["code"] = null;
        }



        $course->name_en = $input["name_en"];
        $course->name_kh = $input["name_kh"];
        $course->name_fr = $input["name_fr"];
        $course->code = $input["code"];
        $course->time_course = $input["time_course"];
        $course->time_td = $input["time_td"];
        $course->time_tp = $input["time_tp"];
        $course->credit = $input["credit"];
        $course->degree_id = $input["degree_id"];
        $course->grade_id = $input["grade_id"];
        $course->department_id = $input["department_id"];
        $course->semester_id = $input["semester_id"];


        $course->create_uid = auth()->id();
        $course->created_at = Carbon::now();

        if ($course->save()) {
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

        if(isset($input['responsible_department_id']) && $input['responsible_department_id'] != ""){
            $courseProgram->responsible_department_id = $input["responsible_department_id"];
        } else {
            $courseProgram->responsible_department_id = null;
        }

        if(isset($input['department_option_id']) && $input['department_option_id'] != ""){
            $courseProgram->department_option_id = $input["department_option_id"];
        } else {
            $courseProgram->department_option_id = null;
        }

        if(!isset($input['is_counted_creditability'])) {
            $courseProgram->is_counted_creditability = false;//---this course program is consider to be studied by student but they dont count it for the student creditability
        }

        if(!isset($input['code']) || $input['code'] == ""){
            $input["code"] = null;
        }

        $courseProgram->name_en = $input["name_en"];
        $courseProgram->name_kh = $input["name_kh"];
        $courseProgram->name_fr = $input["name_fr"];
        $courseProgram->code = $input["code"];
        $courseProgram->time_course = $input["time_course"];
        $courseProgram->time_td = $input["time_td"];
        $courseProgram->time_tp = $input["time_tp"];
        $courseProgram->credit = $input["credit"];
        $courseProgram->degree_id = $input["degree_id"];
        $courseProgram->grade_id = $input["grade_id"];
        $courseProgram->department_id = $input["department_id"];
        $courseProgram->semester_id = $input["semester_id"];

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
