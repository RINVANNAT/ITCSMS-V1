<?php

namespace App\Repositories\Backend\Outcome;
use App\Http\Requests\Backend\Accounting\Outcome\CreateOutcomeRequest;
use App\Http\Requests\Backend\Accounting\Outcome\StoreOutcomeRequest;

/**
 * Interface OutcomeRepositoryContract
 * @package App\Repositories\Backend\Outcome
 */
interface OutcomeRepositoryContract
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
    public function getOutcomesPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllOutcomes($order_by = 'id', $sort = 'asc');

    /**
     * @param  StoreOutcomeRequest $request
     * @return mixed
     */
    public function create(StoreOutcomeRequest $request);

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
