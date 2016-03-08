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
            return SchoolFeeRate::find($id);
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

        $record = SchoolFeeRate::where('promotion_id', $input['promotion_id']);
        if ($input['department_id']!="") {
            $record->where('department_id',$input['department_id']);
        }
        if ($input['degree_id']!="") {
            $record->where('degree_id',$input['degree_id']);
        }
        if ($input['grade_id']!="") {
            $record->where('grade_id',$input['grade_id']);
        }
        if(isset($input['scholarship_id'])){
            if ($input['scholarship_id']!="") {
                $record->where('scholarship_id',$input['scholarship_id']);
            }
        }

        if($record->first()) {
            throw new GeneralException(trans('exceptions.backend.general.already_exists'));
        }

        $schoolFee = new SchoolFeeRate();

        if(isset($input['scholarship_id'])){
            $schoolFee->scholarship_id = $input['scholarship_id'];
            $schoolFee->budget = $input['budget'];
            $schoolFee->budget_currency = $input['budget_currency'];
        }

        $schoolFee->to_pay = $input['to_pay'];
        $schoolFee->to_pay_currency = $input['to_pay_currency'];
        $schoolFee->department_id = $input['department_id']==""?null:$input['department_id'];
        $schoolFee->degree_id = $input['degree_id']==""?null:$input['degree_id'];
        $schoolFee->grade_id = $input['grade_id']==""?null:$input['grade_id'];
        $schoolFee->promotion_id = $input['promotion_id'];
        if(isset($input['description'])){
            $schoolFee->description = $input['description'];
        }
        $schoolFee->active = isset($input['active'])?true:false;

        $schoolFee->created_at = Carbon::now();
        $schoolFee->create_uid = auth()->id();

        if ($schoolFee->save()) {
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

        if(isset($input['scholarship_id'])){
            $schoolFee->scholarship_id = $input['scholarship_id'];
            $schoolFee->budget = $input['budget'];
            $schoolFee->budget_currency = $input['budget_currency'];
        }

        $schoolFee->to_pay = $input['to_pay'];
        $schoolFee->to_pay_currency = $input['to_pay_currency'];
        $schoolFee->department_id = $input['department_id']==""?null:$input['department_id'];
        $schoolFee->degree_id = $input['degree_id']==""?null:$input['degree_id'];
        $schoolFee->grade_id = $input['grade_id']==""?null:$input['grade_id'];
        $schoolFee->promotion_id = $input['promotion_id'];
        if(isset($input['description'])){
            $schoolFee->description = $input['description'];
        }
        $schoolFee->active = isset($input['active'])?true:false;

        $schoolFee->updated_at = Carbon::now();
        $schoolFee->write_uid = auth()->id();

        if ($schoolFee->save()) {
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
