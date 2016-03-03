<?php

namespace App\Repositories\Backend\AcademicYear;


use App\Exceptions\GeneralException;
use App\Models\AcademicYear;
use Carbon\Carbon;

/**
 * Class EloquentRoleRepository
 * @package App\Repositories\Role
 */
class EloquentAcademicYearRepository implements AcademicYearRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(AcademicYear::find($id))) {
            return AcademicYear::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.departments.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getAcademicYearsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return AcademicYear::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllAcademicYears($order_by = 'sort', $sort = 'asc')
    {
        return AcademicYear::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (AcademicYear::where('name_en', $input['name_en'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.academicYears.already_exists'));
        }

        $academicYear = new AcademicYear();

        $academicYear->id = $input['id'];
        $academicYear->name_en = $input['name_en'];
        $academicYear->name_fr = $input['name_fr'];
        $academicYear->name_kh = $input['name_kh'];
        $academicYear->date_start = $input['code'];
        $academicYear->date_end = $input['description'];
        $academicYear->description = $input['is_specialist'];
        $academicYear->created_at = Carbon::now();
        $academicYear->create_uid = auth()->id();

        if ($academicYear->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.academicYears.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $academicYear = $this->findOrThrowException($id);

        $academicYear->name_en = $input['name_en'];
        $academicYear->name_fr = $input['name_fr'];
        $academicYear->name_kh = $input['name_kh'];
        $academicYear->date_start = $input['code'];
        $academicYear->date_end = $input['description'];
        $academicYear->description = $input['is_specialist'];
        $academicYear->updated_at = Carbon::now();
        $academicYear->write_uid = auth()->id();

        if ($academicYear->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.academicYears.update_error'));
    }


}
