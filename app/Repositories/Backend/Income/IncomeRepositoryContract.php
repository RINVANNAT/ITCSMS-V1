<?php

namespace App\Repositories\Backend\Income;

/**
 * Interface IncomeRepositoryContract
 * @package App\Repositories\Backend\Income
 */
interface IncomeRepositoryContract
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
    public function getIncomesPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllIncomes($order_by = 'id', $sort = 'asc');

    /**
     * @param  $input
     * @param $type
     * @return mixed
     */
    public function create($input, $type);

    public function createSimpleIncome($input);

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

    public function refund($id);
}
