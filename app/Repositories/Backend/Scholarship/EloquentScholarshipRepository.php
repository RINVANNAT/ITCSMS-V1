<?php

namespace App\Repositories\Backend\Scholarship;


use App\Exceptions\GeneralException;
use App\Models\Scholarship;
use Carbon\Carbon;

/**
 * Class EloquentScholarshipRepository
 * @package App\Repositories\Backend\Scholarship
 */
class EloquentScholarshipRepository implements ScholarshipRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Scholarship::find($id))) {
            return Scholarship::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.general.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getScholarshipsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Scholarship::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllScholarships($order_by = 'sort', $sort = 'asc')
    {
        return Scholarship::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  String $name
     * @return mixed
     */
    public function search($name)
    {
        return Scholarship::where("name_kh","LIKE","%".$name."%")
            ->orWhere("name_latin", "LIKE", "%".$name."%")
            ->get();
    }


    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {

        $scholarship = new Scholarship();

        $date_start_end = explode(" - ",$input['date_start_end']);

        $date_start = $date_start_end[0];
        $date_end = $date_start_end[1];

        $scholarship->name_en = $input['name_en'];
        $scholarship->name_kh = $input['name_kh'];
        $scholarship->name_fr = $input['name_fr'];
        $scholarship->code = $input['code'];
        $scholarship->isDroppedUponFail = isset($input['isDroppedUponFail'])?true:false;
        $scholarship->duration = $input['duration'];
        $scholarship->founder = $input['founder'];
        $scholarship->active = isset($input['active'])?true:false;
        $scholarship->description = $input['description'];

        $scholarship->start = $date_start;
        $scholarship->stop = $date_end;
        
        $scholarship->created_at = Carbon::now();
        $scholarship->create_uid = auth()->id();

        if ($scholarship->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $scholarship = $this->findOrThrowException($id);

        $date_start_end = explode(" - ",$input['date_start_end']);

        $date_start = $date_start_end[0];
        $date_end = $date_start_end[1];

        $scholarship->name_en = $input['name_en'];
        $scholarship->name_kh = $input['name_kh'];
        $scholarship->name_fr = $input['name_fr'];
        $scholarship->code = $input['code'];
        $scholarship->isDroppedUponFail = isset($input['isDroppedUponFail'])?true:false;
        $scholarship->duration = $input['duration'];
        $scholarship->founder = $input['founder'];
        $scholarship->active = isset($input['active'])?true:false;
        $scholarship->description = $input['description'];

        $scholarship->start = $date_start;
        $scholarship->stop = $date_end;
        
        $scholarship->updated_at = Carbon::now();
        $scholarship->write_uid = auth()->id();

        if ($scholarship->save()) {
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
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
