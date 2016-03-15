<?php

namespace App\Repositories\Backend\SchoolFee;


use App\Exceptions\GeneralException;
use App\Models\SchoolFeeRate;
use Carbon\Carbon;

/**
 * Class EloquentSchoolFeeRepository
 * @package App\Repositories\Backend\SchoolFee
 */
class EloquentSchoolFeeRepository implements SchoolFeeRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(SchoolFeeRate::find($id))) {
            return SchoolFeeRate::with(['departments','grades'])->find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.schoolFees.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getSchoolFeesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return SchoolFeeRate::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllSchoolFees($order_by = 'sort', $sort = 'asc')
    {
        return SchoolFeeRate::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {

        $record = SchoolFeeRate::join('department_school_fee_rate','schoolFeeRates.id','=','department_school_fee_rate.school_fee_rate_id')
            ->join('grade_school_fee_rate','schoolFeeRates.id','=','grade_school_fee_rate.school_fee_rate_id')
            ->where('promotion_id', $input['promotion_id'])
            ->where('degree_id',$input['degree_id'])
            ->where('scholarship_id',$input['scholarship_id']==""?null:$input['scholarship_id'])
            ->whereIn('department_school_fee_rate.department_id',$input['departments'])
            ->whereIn('grade_school_fee_rate.grade_id',$input['grades']);

        if($record->first()) {
            throw new GeneralException(trans('exceptions.backend.general.already_exists'));
        }

        $schoolFee = new SchoolFeeRate();

        $schoolFee->to_pay = $input['to_pay'];
        $schoolFee->to_pay_currency = $input['to_pay_currency'];
        $schoolFee->degree_id = $input['degree_id']==""?null:$input['degree_id'];
        $schoolFee->promotion_id = $input['promotion_id'];
        $schoolFee->scholarship_id = $input['scholarship_id']==""?null:$input['scholarship_id'];

        if(isset($input['description'])){
            $schoolFee->description = $input['description'];
        }
        $schoolFee->active = isset($input['active'])?true:false;

        $schoolFee->created_at = Carbon::now();
        $schoolFee->create_uid = auth()->id();

        if ($schoolFee->save()) {

            if(isset($input['departments'])){
                $schoolFee->departments()->sync($input['departments']);
            }
            if(isset($input['grades'])){
                $schoolFee->grades()->sync($input['grades']);
            }
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.schoolFees.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $schoolFee = $this->findOrThrowException($id);

        $schoolFee->to_pay = $input['to_pay'];
        $schoolFee->to_pay_currency = $input['to_pay_currency'];
        $schoolFee->degree_id = $input['degree_id']==""?null:$input['degree_id'];
        $schoolFee->promotion_id = $input['promotion_id'];
        $schoolFee->scholarship_id = $input['scholarship_id']==""?null:$input['scholarship_id'];

        if(isset($input['description'])){
            $schoolFee->description = $input['description'];
        }
        $schoolFee->active = isset($input['active'])?true:false;

        $schoolFee->created_at = Carbon::now();
        $schoolFee->create_uid = auth()->id();

        if ($schoolFee->save()) {

            if(isset($input['departments'])){
                $schoolFee->departments()->sync($input['departments']);
            }
            if(isset($input['grades'])){
                $schoolFee->grades()->sync($input['grades']);
            }
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.schoolFees.update_error'));
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
