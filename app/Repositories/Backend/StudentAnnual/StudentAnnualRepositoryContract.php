<?php

namespace App\Repositories\Backend\StudentAnnual;
use App\Http\Requests\Backend\Student\StoreStudentRequest;

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
    public function update($id, $input);

    /**
     * @param  $id
     * @return mixed
     */
    public function destroy($id);
}
