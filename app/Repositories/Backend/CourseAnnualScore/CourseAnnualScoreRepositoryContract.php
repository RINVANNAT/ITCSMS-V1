<?php namespace App\Repositories\Backend\CourseAnnualScore;

/**
 * Interface CourseAnnualScoreRepositoryContract
 * @package App\Repositories\Backend\CourseAnnualScore
 */
interface CourseAnnualScoreRepositoryContract
{
    /**
     * @param  $id
     * @return mixed
     */
    public function findOrThrowException($id);



    public function getCourseAnnualScore();

    /**
     * @param  $input
     * @return mixed
     */
    public function create($input);


    // create the record in the table relation of score and percentage

    public function createPercentageScore($scoreId, $percentageId);

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


    public function findScoreId($courseAnnualId, $studentAnnualId);
}
