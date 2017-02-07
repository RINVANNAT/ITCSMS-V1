<?php namespace App\Repositories\Backend\CourseAnnualClass;

/**
 * Interface CourseAnnualRepositoryContract
 * @package App\Repositories\Backend\CourseAnnual
 */
interface CourseAnnualClassRepositoryContract
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
    public function getCourseAnnualClassesPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllCourseAnnualClasses($order_by = 'id', $sort = 'asc');

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
