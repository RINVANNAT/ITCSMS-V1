<?php

namespace App\Repositories\Backend\Group;


use App\Exceptions\GeneralException;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentGradeRepository
 * @package App\Repositories\Backend\Grade
 */
class EloquentGroupRepository implements GroupRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Group::find($id))) {
            return Group::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.group.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getGroupPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Group::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllGroups($order_by = 'sort', $sort = 'asc')
    {
        return Group::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param $input
     * @return Grade
     * @throws GeneralException
     */
    public function create($input)
    {
       /* if (Group::where('code', $input['code'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.group.already_exists'));
        }*/

        $group = new Group();
        $group->code = $input['code'];
        $group->created_at = Carbon::now();

        if ($group->save()) {
            return $group;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.grades.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $group = $this->findOrThrowException($id);

        $group->code = $input['code'];
        $group->updated_at = Carbon::now();

        if ($group->save()) {
            return $group;
        }

        throw new GeneralException(trans('exceptions.configuration.grades.update_error'));
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

    /**
     * @param $input
     * @return bool
     */

    public function storeGroupStudentAnnual($input)
    {

        DB::beginTransaction();

        try {

            DB::table('group_student_annuals')->insert($input);
            DB::commit();
            return true;

        } catch (\Exception $e) {

            DB::rollback();

           return false;
        }


    }


    /**
     * @param $studentProp
     * @param $studentAnnuals
     * @param $departments
     * @return array
     */

    public function toCreateGroup($studentProp, $studentAnnuals, $departments, $groupItem, $grouptStudentAnnuals)
    {
        // TODO: Implement toCreateGroup() method.

        $count = 0;
        $unCount = 0;

        $missedIds = '';
        $missed_id = [];
        $missed = false;
        $checkDept = false;

        foreach($studentProp as $stu) {

            if(isset($studentAnnuals[$stu['student_id']])) {

                if(!isset($grouptStudentAnnuals[ $studentAnnuals[$stu['student_id']]->id ])) {

                    $student_annual = $studentAnnuals[$stu['student_id']];

                    if(isset($departments[$stu['department_code']])) {

                        $department = $departments[$stu['department_code']];

                        if($department['is_vocational']) {

                            $newGroupStudentAnnual = [
                                'student_annual_id' => $student_annual->id,
                                'semester_id' => $stu['semester'],
                                'department_id' => $department['id'],
                                'group_id' => $groupItem->id,
                                'created_at' => Carbon::now()
                            ];

                        } else {

                            $newGroupStudentAnnual = [
                                'student_annual_id' => $student_annual->id,
                                'semester_id' => $stu['semester'],
                                'department_id' => null,
                                'group_id' => $groupItem->id,
                                'created_at' => Carbon::now()
                            ];

                        }
                        $store =  $this->storeGroupStudentAnnual($newGroupStudentAnnual);

                        if($store) {
                            $count++;
                        }

                    } else {
                        $checkDept = true;
                    }
                } else {

                    $unCount++;

                }

            } else {
                /*---some student are not in our database -----*/

                $missedIds .=$stu['student_id'].',';
                $missed_id[] = $stu['student_id'];
                $missed = true;
            }
        }

        if($missed) {

            return [
                'status' => true,
                'message' => 'Missing Student: ( '. $missedIds.' )'. ' Please check!',
                'missed_id' => $missed_id
            ];
        }

        if($checkDept) {

            return [
                'status' => false,
                'message' => 'Cell has no depatement code'
            ];
        }

        if($count == count($studentProp)) {

            return [
                'status' => true,
                'message' => 'Group student saved!'
            ];
        } else {

            if($unCount > 0) {

                return [
                    'status' => true,
                    'message' => 'Some students are already have groups!'
                ];

            }
            return [
                'status' => false,
                'message' => 'Cannot Save Group Student!'
            ];
        }
    }
}
