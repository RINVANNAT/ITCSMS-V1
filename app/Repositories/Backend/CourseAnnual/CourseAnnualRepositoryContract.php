<?php namespace App\Repositories\Backend\CourseAnnual;

/**
 * Interface CourseAnnualRepositoryContract
 * @package App\Repositories\Backend\CourseAnnual
 */
interface CourseAnnualRepositoryContract
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
    public function getCourseAnnualsPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllCourseAnnuals($order_by = 'id', $sort = 'asc');

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
     * @param  $input
     * @return mixed
     */
    public function update_score_per($id, $input);

    /**
     * @param  $id
     * @return mixed
     */
    public function destroy($id);
}
