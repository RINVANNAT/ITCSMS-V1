<?php namespace App\Repositories\Backend\AcademicYear;

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
        if (AcademicYear::where('name_latin', $input['name_latin'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.academicYears.already_exists'));
        }

        $date_start_end = explode(" - ",$input['date_start_end']);

        $date_start = $date_start_end[0];
        $date_end = $date_start_end[1];
        $academicYear = new AcademicYear();

        $academicYear->id = $input['id'];
        $academicYear->name_latin = $input['name_latin'];
        $academicYear->name_kh = $input['name_kh'];
        $academicYear->date_start = $date_start;
        $academicYear->date_end = $date_end;
        $academicYear->description = $input['description'];
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

        $date_start_end = explode(" - ",$input['date_start_end']);

        $date_start = $date_start_end[0];
        $date_end = $date_start_end[1];

        $academicYear->name_latin = $input['name_latin'];
        $academicYear->name_kh = $input['name_kh'];
        $academicYear->date_start = $date_start;
        $academicYear->date_end = $date_end;
        $academicYear->description = $input['description'];
        $academicYear->updated_at = Carbon::now();
        $academicYear->write_uid = auth()->id();

        if ($academicYear->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.academicYears.update_error'));
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {

        $academicYear = $this->findOrThrowException($id);

        //Don't delete the role is there are users associated
        if ($academicYear->student_annuals()->count() > 0) {
            throw new GeneralException(trans('exceptions.backend.general.has_reference'));
        }

        if ($academicYear->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }


}
