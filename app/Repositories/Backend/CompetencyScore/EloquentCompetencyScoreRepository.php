<?php

namespace App\Repositories\Backend\CompetencyScore;


use App\Exceptions\GeneralException;
use App\Models\CompetencyScore;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\UserLog;

/**
 * Class EloquentCourseAnnualScoreRepository
 * @package App\Repositories\Backend\CourseAnnualScore
 */
class EloquentCompetencyScoreRepository implements CompetencyScoreRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(CompetencyScore::find($id))) {
            return CompetencyScore::find($id);
        }
        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param $input
     * @return bool
     * @throws GeneralException
     */
    public function create($input)
    {

        //$competencyScore = new CompetencyScore();

     /*   $competencyScore->course_annual_id = $input['course_annual_id'];
        $competencyScore->student_annual_id = $input['student_annual_id'];
        $competencyScore->competency_id = $input['competency_id'];
        $competencyScore->score = isset($input['score'])?$input['score']:null;
        $competencyScore->created_at = Carbon::now();
        $competencyScore->create_uid = auth()->id();*/

//        if ($competencyScore->save()) {
//            $this->getUserLog($competencyScore,'CompetencyScore', 'Create' );
//            return $competencyScore;
//        }

        $save = DB::table('competency_scores')->insert($input);

        if($save) {
            $this->getUserLog($input,'CompetencyScore', 'Create' );
            return true;
        }
        throw new GeneralException(trans('exceptions.backend.general.create_error'));
    }





    public function getCompetencyScore($studentAnnualIds, $courseAnnualId, $competencyId){


        $competenciesScores = DB::table('competency_scores')
            ->where(function($query) use ($courseAnnualId, $studentAnnualIds, $competencyId){
                $query
                    ->where('competency_id', '=', $competencyId)
                    ->where('course_annual_id', '=', $courseAnnualId)
                    ->whereIn('student_annual_id', $studentAnnualIds);
            })->get();

        $competencyScoresKeyByIds = collect($competenciesScores)->keyBy('student_annual_id')->toArray();

        return $competencyScoresKeyByIds;

    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {

        $competencyScore = $this->findOrThrowException($id);

        if($input['score'] == null) {
            $competencyScore->score = null;
        } else {

            //$competencyScore->score = isset($input['score'])?substr($input['score'],0,5):null;
            $competencyScore->score = isset($input['score'])?$input['score']:null;
        }

        $competencyScore->course_annual_id = $input['course_annual_id'];
        $competencyScore->student_annual_id = $input['student_annual_id'];
        $competencyScore->competency_id = $input['competency_id'];

        $competencyScore->updated_at = Carbon::now();
        $competencyScore->write_uid = auth()->id();

        if ($competencyScore->save()) {

            $this->getUserLog($competencyScore,'CompetencyScore', 'Update' );
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

            $this->getUserLog($model,'Score', 'Delete' );
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }


    /**
     * @param $data
     * @param $model
     * @param $action
     */
    public function getUserLog($data, $model, $action) {

        $storeData = json_encode($data);
        UserLog::log([
            'model' => $model,
            'action'   => $action, // Import, Create, Delete, Update
            'data'     => $storeData // if it is create action, store only the new id.
        ]);

    }
}
