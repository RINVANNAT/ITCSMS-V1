<?php

namespace App\Repositories\Backend\ResitStudentAnnual;


use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Reporting\StoreReportingRequest;
use App\Http\Requests\Backend\Reporting\UpdateReportingRequest;
use App\Http\Requests\Request;
use App\Models\ResitStudentAnnual;
use Carbon\Carbon;

/**
 * Class EloquentReportingRepository
 * @package App\Repositories\Backend\Reporting
 */
class EloquentResitStudentAnnualRepository implements ResitStudentAnnualRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(ResitStudentAnnual::find($id))) {
            return ResitStudentAnnual::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.reportings.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getResitStudentAnnualPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return ResitStudentAnnual::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllResitStudentAnnuals($order_by = 'sort', $sort = 'asc')
    {
        return ResitStudentAnnual::orderBy($order_by, $sort)
            ->get();
    }
    /**
     * @param  StoreReportingRequest $request
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {

        $resitStudentAnnual = new ResitStudentAnnual();

        $resitStudentAnnual->course_annual_id = isset($input['course_annual_id'])?$input['course_annual_id']:null;
        $resitStudentAnnual->student_annual_id = isset($input['student_annual_id'])?$input['student_annual_id']:null;
        $resitStudentAnnual->resit_score = isset($input['resit_score'])?$input['resit_score']:null;
        $resitStudentAnnual->resit_room = isset($input['resit_room'])?$input['resit_room']:null;
        $resitStudentAnnual->semester_id = isset($input['semester_id'])?$input['semester_id']:null;
        $resitStudentAnnual->created_at = Carbon::now();
        $resitStudentAnnual->create_uid = auth()->id();

        if ($resitStudentAnnual->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.create_error'));
    }

    /**
     * @param  $id
     * @param  UpdateReportingRequest $request
     * @throws GeneralException
     * @return bool
     */
    public function update($id,  $input)
    {

        $resitStudentAnnual = $this->findOrThrowException($id);

            $resitStudentAnnual->title = $input['title'];
            $resitStudentAnnual->description = isset($input['description'])?$input['description']:null;

            $resitStudentAnnual->updated_at = Carbon::now();
            $resitStudentAnnual->write_uid = auth()->id();

            if ($resitStudentAnnual->save()) {
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

        $resitStudentAnnual = $this->findOrThrowException($id);

        if ($resitStudentAnnual->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
