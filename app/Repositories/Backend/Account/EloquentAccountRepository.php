<?php

namespace App\Repositories\Backend\Account;


use App\Exceptions\GeneralException;
use App\Models\Account;
use Carbon\Carbon;

/**
 * Class EloquentAccountRepository
 * @package App\Repositories\Backend\Account
 */
class EloquentAccountRepository implements AccountRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Account::find($id))) {
            return Account::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.accounts.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getAccountsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Account::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @param  bool    $withPermissions
     * @return mixed
     */
    public function getAllAccounts($order_by = 'sort', $sort = 'asc', $withPermissions = false)
    {
        return Account::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Account::where('name_en', $input['name_en'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.accounts.already_exists'));
        }

        $count = Account::count();

        $account = new Account();
        $account->id = $count+1;
        $account->name_en = $input['name'];
        $account->description = $input['description'];
        $account->active = $input['active'];
        $account->created_at = Carbon::now();
        $account->create_uid = auth()->id();

        if ($account->save()) {
            return true;
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
        $account = $this->findOrThrowException($id);

        $account->name_en = $input['name'];
        $account->description = $input['description'];
        $account->active = $input['active'];
        $account->updated_at = Carbon::now();
        $account->write_uid = auth()->id();

        if ($account->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.accounts.update_error'));
    }


}
