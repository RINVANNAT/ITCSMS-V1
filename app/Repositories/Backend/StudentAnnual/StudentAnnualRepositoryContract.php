<?php

namespace App\Repositories\Backend\StudentAnnual;
use App\Http\Requests\Backend\Student\StoreStudentRequest;
use App\Http\Requests\Backend\Student\UpdateStudentRequest;

/**
 * Interface StudentAnnualRepositoryContract
 * @package App\Repositories\Backend\StudentAnnual
 */
interface StudentAnnualRepositoryContract
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
    public function getStudentAnnualsPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllStudentAnnuals($order_by = 'id', $sort = 'asc');

    public function register($candidate);

    /**
     * @param  $input
     * @return mixed
     */
    public function create(StoreStudentRequest $request);

    /**
     * @param  $id
     * @param  $input
     * @return mixed
     */
    public function update($id, UpdateStudentRequest $request);

    /**
     * @param  $id
     * @return mixed
     */
    public function destroy($id);

    public function registerStudentDUT($candidate, $department_id);
}
