<?php

namespace App\Repositories\Backend\Average;


use App\Exceptions\GeneralException;
use App\Models\Average;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentAverageRepository
 * @package App\Repositories\Backend\Average
 */
class EloquentAverageRepository implements AverageRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Average::find($id))) {
            return Average::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.accounts.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getAveragesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Average::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @param  bool    $withPermissions
     * @return mixed
     */
    public function getAllAverages($order_by = 'sort', $sort = 'asc', $withPermissions = false)
    {
        return Average::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        $average = new Average();
        $average->course_annual_id = $input['course_annual_id'];
        $average->student_annual_id = $input['student_annual_id'];
        $average->total_average_id = isset($input['total_average_id'])? $input['total_average_id']:null;
        $average->description = isset($input['description'])?$input['description']:null;
        $average->average = isset($input['average'])?$input['average']:null;
        $average->created_at = Carbon::now();
        $average->create_uid = auth()->id();

        if ($average->save()) {

            return $average;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.accounts.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {

//        dd($input);
        $average = $this->findOrThrowException($id);


        $average->course_annual_id = isset($input['course_annual_id'])?$input['course_annual_id']:$average->course_annual_id;
        $average->student_annual_id = isset($input['student_annual_id'])?$input['student_annual_id']:$average->student_annual_id;
        $average->total_average_id = isset($input['total_average_id'])? $input['total_average_id']:null;
        $average->average = isset($input['average'])? $input['average']:0;
        $average->description = isset($input['description'])?$input['description']:$average->description;
        $average->created_at = Carbon::now();
        $average->create_uid = auth()->id();

        if ($average->save()) {

//            $average->scores()->saveMany($products);
            return $average;
        }

        throw new GeneralException(trans('exceptions.configuration.accounts.update_error'));
    }

    public function storeTableRelation($averageId, $scoreId) {

        $save = DB::table('average_scores')->insert([
            ['average_id' => $averageId, 'score_id' => $scoreId],
        ]);


        return $save;
    }

    public function findAverageByCourseIdAndStudentId($courseAnnualId, $studentAnnualId) {

        $average = DB::table('averages')->where([
            ['course_annual_id', $courseAnnualId],
            ['student_annual_id', $studentAnnualId]
        ])->first();

        return $average;
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
