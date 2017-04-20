<?php

namespace App\Repositories\Backend\Average;

/**
 * Interface AverageRepositoryContract
 * @package App\Repositories\Backend\Average
 */
interface AverageRepositoryContract
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
    public function getAveragesPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllAverages($order_by = 'id', $sort = 'asc');

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

    public function storeTableRelation($averageID, $scoreID);

    public function findAverageByCourseIdAndStudentId($courseAnnualId, $studentAnnualId);

    public function updateResitScore($input);


}
