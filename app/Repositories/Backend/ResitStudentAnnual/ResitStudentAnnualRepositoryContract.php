<?php

namespace App\Repositories\Backend\ResitStudentAnnual;
use App\Http\Requests\Request;

/**
 * Interface ResitStudentAnnualRepositoryContract
 * @package App\Repositories\Backend\ResitStudentAnnual
 */
interface ResitStudentAnnualRepositoryContract
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
    public function getResitStudentAnnualPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllResitStudentAnnuals($order_by = 'id', $sort = 'asc');

    /**
     * @param  StoreReportingRequest $request
     * @return mixed
     */
    public function create($input);

    /**
     * @param  $id
     * @param  UpdateReportingRequest $request
     * @return mixed
     */
    public function update($id, $input);

    /**
     * @param  $id
     * @return mixed
     */
    public function destroy($id);
}
