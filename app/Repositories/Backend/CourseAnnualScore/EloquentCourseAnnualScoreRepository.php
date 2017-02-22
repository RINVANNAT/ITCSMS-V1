<?php

namespace App\Repositories\Backend\CourseAnnualScore;


use App\Exceptions\GeneralException;
use App\Models\CourseAnnual;
use App\Models\Score;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentCourseAnnualScoreRepository
 * @package App\Repositories\Backend\CourseAnnualScore
 */
class EloquentCourseAnnualScoreRepository implements CourseAnnualScoreRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Score::find($id))) {
            return Score::find($id);
        }
        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        $courseAnnualScore = new Score();

        $courseAnnualScore->course_annual_id = $input['course_annual_id'];
        $courseAnnualScore->student_annual_id = $input['student_annual_id'];
        $courseAnnualScore->academic_year_id = $input['academic_year_id'];
        $courseAnnualScore->semester_id = $input['semester_id'];
        $courseAnnualScore->grade_id = $input['grade_id'];
        $courseAnnualScore->degree_id = $input['degree_id'];
        $courseAnnualScore->department_id = $input['department_id'];
        $courseAnnualScore->score = isset($input['score'])?$input['score']:null;
        $courseAnnualScore->score_absence = isset($input['score_absence'])?$input['score_absence']:null;
        $courseAnnualScore->created_at = Carbon::now();
        $courseAnnualScore->create_uid = auth()->id();
        if ($courseAnnualScore->save()) {
            return $courseAnnualScore;
        }
        throw new GeneralException(trans('exceptions.backend.general.create_error'));
    }



    public function getCourseAnnualScore(){

    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {

        //---we need to update the score and score_absence only

        $courseAnnualScore = $this->findOrThrowException($id);

//        $courseAnnualScore->course_annual_id = $input['course_annual_id'];
//        $courseAnnualScore->student_annual_id = $input['student_annual_id'];
//        $courseAnnualScore->academic_year_id = $input['academic_year_id'];
//        $courseAnnualScore->semester_id = $input['semester_id'];
//        $courseAnnualScore->grade_id = $input['grade_id'];
//        $courseAnnualScore->degree_id = $input['degree_id'];
//        $courseAnnualScore->department_id = $input['department_id'];


        if($input['score'] == null) {
            $courseAnnualScore->score = null;
        } else {

            $courseAnnualScore->score = isset($input['score'])?$input['score']:null;
        }
        $courseAnnualScore->score_absence = isset($input['score_absence'])?$input['score_absence']:$courseAnnualScore->score_absence;
        $courseAnnualScore->updated_at = Carbon::now();
        $courseAnnualScore->write_uid = auth()->id();

        if ($courseAnnualScore->save()) {

//            $courseAnnualScore->percentageScore()->sync($input['percentage_score']);
            return true;
        }
        throw new GeneralException(trans('exceptions.backend.general.update_error'));
    }



    public function findScoreId($courseAnnualId, $studentAnnualId) {

        $score = Score::where([
            ['course_annual_id', $courseAnnualId],
            ['student_annual_id', $studentAnnualId]
        ])->get();

        return $score;
    }


    public function createPercentageScore($scoreId, $percentageId) {

        $save = DB::table('percentage_scores')->insert([
            ['score_id'=> $scoreId, 'percentage_id'=> $percentageId]
        ]);

        if($save) {
            return true;
        }
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
