<?php

namespace App\Repositories\Backend\Configuration;


use App\Exceptions\GeneralException;
use App\Models\Configuration;
use Carbon\Carbon;

/**
 * Class EloquentConfigurationRepository
 * @package App\Repositories\Backend\Configuration
 */
class EloquentConfigurationRepository implements ConfigurationRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Configuration::find($id))) {
            return Configuration::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getConfigurationsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Configuration::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @param  bool    $withPermissions
     * @return mixed
     */
    public function getAllConfigurations($order_by = 'sort', $sort = 'asc', $withPermissions = false)
    {
        return Configuration::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Configuration::where('key', $input['key'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.already_exists'));
        }

        $configuration = new Configuration();

        $configuration->key = $input['key'];
        $configuration->value = $input['key'];
        if(isset($input['description']))$configuration->description = $input['description'];
        
        $configuration->created_at = Carbon::now();
        $configuration->create_uid = auth()->id();

        if ($configuration->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $configuration = $this->findOrThrowException($id);

        $configuration->key = $input['key'];
        $configuration->description = $input['description'];
        $configuration->value = $input['value'];
        $configuration->updated_at = Carbon::now();
        $configuration->write_uid = auth()->id();

        if ($configuration->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.update_error'));
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
