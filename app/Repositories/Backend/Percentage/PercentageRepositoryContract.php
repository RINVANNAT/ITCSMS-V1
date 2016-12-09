<?php

namespace App\Repositories\Backend\Percentage;

/**
 * Interface PercentageRepositoryContract
 * @package App\Repositories\Backend\Percentage
 */
interface PercentageRepositoryContract
{
    /**
     * @param  $id
     * @return mixed
     */
    public function findOrThrowException($id);


    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllPercentages($order_by = 'id', $sort = 'asc');

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
}
