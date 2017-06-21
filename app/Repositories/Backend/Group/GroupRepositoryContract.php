<?php

namespace App\Repositories\Backend\Group;

/**
 * Interface GradeRepositoryContract
 * @package App\Repositories\Backend\Grade
 */
interface GroupRepositoryContract
{
    /**
     * @param  $id
     * @return mixed
     */
    public function findOrThrowException($id);

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getGroupPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllGroups($order_by = 'id', $sort = 'asc');

    /**
     * @param  $input
     * @return mixed
     */
    public function create($input);

    /**
     * @param  $id
     * @param  $input
     * @return mixed
     */
    public function update($id, $input);

    /**
     * @param  $id
     * @return mixed
     */
    public function destroy($id);


    /**
     * @param $input
     * @return mixed
     */
    public function storeGroupStudentAnnual($input);


    /**
     * @param $studentProp
     * @param $studentAnnuals
     * @param $departments
     * @param $groupItem
     * @return mixed
     */

    public function toCreateGroup($studentProp, $studentAnnuals, $departments, $groupItem);
}
