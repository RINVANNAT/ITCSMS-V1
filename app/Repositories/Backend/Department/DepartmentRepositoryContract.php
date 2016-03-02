<?php

namespace App\Repositories\Backend\Department;

/**
 * Interface RoleRepositoryContract
 * @package App\Repositories\Role
 */
interface DepartmentRepositoryContract
{
    /**
     * @param  $id
     * @param  bool    $withPermissions
     * @return mixed
     */
    public function findOrThrowException($id, $withPermissions = false);

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getDepartmentsPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @param  bool    $withPermissions
     * @return mixed
     */
    public function getAllDepartments($order_by = 'id', $sort = 'asc', $withPermissions = false);

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


}
