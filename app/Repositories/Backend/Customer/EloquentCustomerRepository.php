<?php

namespace App\Repositories\Backend\Customer;


use App\Exceptions\GeneralException;
use App\Models\Customer;
use Carbon\Carbon;

/**
 * Class EloquentCustomerRepository
 * @package App\Repositories\Backend\Customer
 */
class EloquentCustomerRepository implements CustomerRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Customer::find($id))) {
            return Customer::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.accounting.customers.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getCustomersPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Customer::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllCustomers($order_by = 'sort', $sort = 'asc')
    {
        return Customer::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if(isset($input['identity_number']) && $input['identity_number'] != ""){
            if (Customer::where('identity_number', $input['identity_number'])->first()) {
                throw new GeneralException(trans('exceptions.backend.general.already_exists'));
            }
        }

        $customer = new Customer();

        $customer->name = $input['name'];
        $customer->address = $input['address'];
        $customer->phone = $input['phone'];
        $customer->email = $input['email'];
        $customer->company = $input['company'];
        $customer->identity_number = $input['identity_number'];
        $customer->active = isset($input['active'])?true:false;

        $customer->created_at = Carbon::now();
        $customer->create_uid = auth()->id();

        if ($customer->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.accounting.customers.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $customer = $this->findOrThrowException($id);

        $customer->name = $input['name'];
        $customer->address = $input['address'];
        $customer->phone = $input['phone'];
        $customer->email = $input['email'];
        $customer->company = $input['company'];
        $customer->identity_number = $input['identity_number'];
        $customer->active = isset($input['active'])?true:false;

        $customer->updated_at = Carbon::now();
        $customer->write_uid = auth()->id();

        if ($customer->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.accounting.customers.update_error'));
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
