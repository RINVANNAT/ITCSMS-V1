<?php

namespace App\Repositories\Backend\Exam;
use App\Http\Requests\Backend\Exam\StoreEntranceExamScoreRequest;

/**
 * Interface ExamRepositoryContract
 * @package App\Repositories\Backend\Exam
 */
interface ExamRepositoryContract
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
    public function getExamsPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllExams($order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getEntranceExams($order_by = 'id', $sort = 'asc');

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getFinalExams($order_by = 'id', $sort = 'asc');


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



    public function requestInputScoreForm($exam_id, $request, $number_correction);

    public function insertCandidateScore($exam_id, $requestDatas);

    public function addNewCorrectionCandidateScore($examId, StoreEntranceExamScoreRequest $request);
}
