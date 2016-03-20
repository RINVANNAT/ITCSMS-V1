<?php

namespace App\Repositories\Backend\Reporting;
use App\Http\Requests\Backend\Reporting\StoreReportingRequest;
use App\Http\Requests\Backend\Reporting\UpdateReportingRequest;

/**
 * Interface ReportingRepositoryContract
 * @package App\Repositories\Backend\Reporting
 */
interface ReportingRepositoryContract
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
    public function getReportingsPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllReportings($order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getEntranceReportings($order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getFinalReportings($order_by = 'id', $sort = 'asc');


    /**
     * @param  StoreReportingRequest $request
     * @return mixed
     */
    public function create(StoreReportingRequest $request);

    /**
     * @param  $id
     * @param  UpdateReportingRequest $request
     * @return mixed
     */
    public function update($id, UpdateReportingRequest $request);

    /**
     * @param  $id
     * @return mixed
     */
    public function destroy($id);
}
