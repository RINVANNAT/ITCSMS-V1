<?php namespace App\Repositories\Backend\CompetencyScore;

/**
 * Interface CourseAnnualScoreRepositoryContract
 * @package App\Repositories\Backend\CourseAnnualScore
 */
interface CompetencyScoreRepositoryContract
{
    /**
     * @param  $id
     * @return mixed
     */
    public function findOrThrowException($id);



    public function getCompetencyScore($studentAnnualIds, $courseAnnualId, $competencyId);

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
