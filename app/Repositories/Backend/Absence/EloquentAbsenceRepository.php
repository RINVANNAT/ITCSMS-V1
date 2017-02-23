<?php namespace App\Repositories\Backend\Absence;

use App\Exceptions\GeneralException;
use App\Models\AcademicYear;
use App\Models\Absence;
use Carbon\Carbon;

/**
 * Class EloquentAbsenceRepository
 * @package App\Repositories\Role
 */
class EloquentAbsenceRepository implements AbsenceRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Absence::find($id))) {
            return Absence::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.departments.not_found'));
    }


    public function findIfExist($courseAnnualID, $studentAnnualID) {
        $absence = Absence::where([
            ['course_annual_id', $courseAnnualID],
            ['student_annual_id', $studentAnnualID]
        ])
            ->first();

        return $absence;
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getAbsencePaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Absence::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllAbsence($order_by = 'sort', $sort = 'asc')
    {
        return Absence::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
//        if (AcademicYear::where('name_latin', $input['name_latin'])->first()) {
//            throw new GeneralException(trans('exceptions.backend.configuration.academicYears.already_exists'));
//        }

        $absence = new Absence();

        $absence->course_annual_id = $input['course_annual_id'];
        $absence->student_annual_id = $input['student_annual_id'];
        $absence->num_absence = ($input['num_absence'] == '')?null:$input['num_absence'] ;
        $absence->created_at = Carbon::now();
        $absence->create_uid = auth()->id();

        if ($absence->save()) {
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
        $absence = $this->findOrThrowException($id);

        $absence->course_annual_id = isset($input['course_annual_id'])?$input['course_annual_id']:$absence->course_annual_id;
        $absence->student_annual_id = isset($input['student_annual_id'])?$input['student_annual_id']:$absence->student_annual_id;
        $absence->num_absence = ($input['num_absence'] != null)? ($input['num_absence'] == '')?null:$input['num_absence']:null;

        $absence->updated_at = Carbon::now();
        $absence->write_uid = auth()->id();

        if ($absence->save()) {
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

        $absence = $this->findOrThrowException($id);

        //Don't delete the role is there are users associated
//        if ($absence->student_annuals()->count() > 0) {
//            throw new GeneralException(trans('exceptions.backend. .has_reference'));
//        }

        if ($absence->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }


}
