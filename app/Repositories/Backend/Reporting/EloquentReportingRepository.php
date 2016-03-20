<?php

namespace App\Repositories\Backend\Reporting;


use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Reporting\StoreReportingRequest;
use App\Http\Requests\Backend\Reporting\UpdateReportingRequest;
use App\Models\Reporting;
use Carbon\Carbon;

/**
 * Class EloquentReportingRepository
 * @package App\Repositories\Backend\Reporting
 */
class EloquentReportingRepository implements ReportingRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Reporting::find($id))) {
            return Reporting::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.reportings.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getReportingsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Reporting::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllReportings($order_by = 'sort', $sort = 'asc')
    {
        return Reporting::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getEntranceReportings($order_by = 'sort', $sort = 'asc')
    {
        return Reporting::where()->orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getFinalReportings($order_by = 'sort', $sort = 'asc')
    {
        return Reporting::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  StoreReportingRequest $request
     * @throws GeneralException
     * @return bool
     */
    public function create(StoreReportingRequest $request)
    {
        $input = $request->all();

        $reporting = new Reporting();

        $reporting->title = $input['title'];
        $reporting->description = isset($input['description'])?$input['description']:null;

        if($request->file('image')!= null){
            $milliseconds = round(microtime(true) * 1000);
            $imageName = "reporting_".$milliseconds . '.' .$request->file('image')->getClientOriginalExtension();
            $reporting->image = $imageName;
            $request->file('image')->move(
                base_path() . '/public/img/reporting/', $imageName
            );
        } else {
            $reporting->image = null;
        }

        $reporting->created_at = Carbon::now();
        $reporting->create_uid = auth()->id();

        if ($reporting->save()) {
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
    public function update($id, UpdateReportingRequest $request)
    {
        $input = $request->all();
        $reporting = $this->findOrThrowException($id);
        if($reporting->status == "In Progress"){
            throw new GeneralException(trans('exceptions.backend.general.no_permission'));
        } else {
            $reporting->title = $input['title'];
            $reporting->description = isset($input['description'])?$input['description']:null;

            if($request->file('image')!= null){
                $milliseconds = round(microtime(true) * 1000);
                $imageName = "reporting_".$milliseconds . '.' .$request->file('image')->getClientOriginalExtension();
                $reporting->image = $imageName;
                $request->file('image')->move(
                    base_path() . '/public/img/reporting/', $imageName
                );
            } else {
                $reporting->image = null;
            }

            $reporting->updated_at = Carbon::now();
            $reporting->write_uid = auth()->id();

            if ($reporting->save()) {
                return true;
            }

            throw new GeneralException(trans('exceptions.backend.general.update_error'));
        }
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {

        $reporting = $this->findOrThrowException($id);

        if ($reporting->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
