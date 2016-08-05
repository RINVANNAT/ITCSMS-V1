<?php

namespace App\Repositories\Backend\Exam;


use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Exam\StoreEntranceExamScoreRequest;
use App\Models\Exam;
use Carbon\Carbon;
use DB;
use Object;
use phpDocumentor\Reflection\Types\Object_;

/**
 * Class EloquentExamRepository
 * @package App\Repositories\Backend\Exam
 */
class EloquentExamRepository implements ExamRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Exam::find($id))) {
            return Exam::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.exams.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getExamsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Exam::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllExams($order_by = 'sort', $sort = 'asc')
    {
        return Exam::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getEntranceExams($order_by = 'sort', $sort = 'asc')
    {
        return Exam::where()->orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getFinalExams($order_by = 'sort', $sort = 'asc')
    {
        return Exam::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Exam::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.general.already_exists'));
        }

        $date_start_end = explode(" - ",$input['date_start_end']);

        $date_start = $date_start_end[0];
        $date_end = $date_start_end[1];

        $exam = new Exam();

        $exam->name = $input['name'];
        $exam->date_start = $date_start;
        $exam->date_end = $date_end;
        $exam->active = isset($input['active'])?true:false;
        $exam->description = $input['description'];
        $exam->academic_year_id = $input['academic_year_id'];
        $exam->type_id = $input['type_id'];

        $exam->created_at = Carbon::now();
        $exam->create_uid = auth()->id();

        if ($exam->save()) {
            return $exam->id;
        }

        throw new GeneralException(trans('exceptions.backend.general.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $exam = $this->findOrThrowException($id);

        $date_start_end = explode(" - ",$input['date_start_end']);

        $date_start = $date_start_end[0];
        $date_end = $date_start_end[1];

        $exam->name = $input['name'];
        $exam->date_start = $date_start;
        $exam->date_end = $date_end;
        $exam->active = isset($input['active'])?true:false;
        $exam->description = $input['description'];
        $exam->academic_year_id = $input['academic_year_id'];
        $exam->type_id = $input['type_id'];

        $exam->created_at = Carbon::now();
        $exam->create_uid = auth()->id();

        if ($exam->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.update_error'));
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {

        $exam = $this->findOrThrowException($id);

        //Don't delete the role is there are users associated
        if ($exam->candidates()->count() > 0) {
            throw new GeneralException(trans('exceptions.backend.exams.has_candidate'));
        }

        $exam->rooms()->sync([]);
        $exam->employees()->sync([]);
        $exam->students()->sync([]);

        if ($exam->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }

    public function requestInputScoreForm ($exam_id, $request, $number_correction) {

        $mergedObjects = [];
        $candidateScoreForm = [];
        $candidates = DB::table('candidates')
            ->leftJoin('entranceExamCourses', 'entranceExamCourses.exam_id', '=', 'candidates.exam_id')
            ->where([
                ['candidates.room_id', '=', $request->room_id],
                ['candidates.exam_id', '=', $exam_id],
                ['entranceExamCourses.id', '=', $request->entrance_course_id],
            ])

            ->select( 'candidates.id as candidate_id', 'candidates.register_id', 'entranceExamCourses.name_kh as course_name','entranceExamCourses.total_question')
            ->orderBy('candidates.register_id', 'ASC')->get();

        foreach($candidates as $candidate) {
            $candidateExamScore = DB::table('candidateEntranceExamScores')
                                    ->where([
                                        ['candidateEntranceExamScores.entrance_exam_course_id', '=', $request->entrance_course_id],
                                        ['candidateEntranceExamScores.candidate_id', '=', $candidate->candidate_id],
                                        ['candidateEntranceExamScores.sequence', '=', $number_correction]
                                    ])->select('score_c', 'score_w', 'score_na', 'sequence', 'candidateEntranceExamScores.id as candidate_score_id')->get();
            array_push($mergedObjects, (object) array('candidateProperties' => $candidate, 'scoreProperties' => $candidateExamScore) );
        }
        usort($mergedObjects, array($this, "sortObject"));
        foreach ($mergedObjects as $mergedObject) {

            if($mergedObject->scoreProperties) {

                foreach ($mergedObject->scoreProperties as $scoreProperty) {

                    if ($number_correction == $scoreProperty->sequence) {

                        $element = (object)array(
                            'register_id' => $mergedObject->candidateProperties->register_id,
                            'course_name' => $mergedObject->candidateProperties->course_name,
                            'total_question' => $mergedObject->candidateProperties->total_question,
                            'candidate_id' => $mergedObject->candidateProperties->candidate_id,
                            'score_c' => $scoreProperty->score_c,
                            'score_w' => $scoreProperty->score_w,
                            'score_na' => $scoreProperty->score_na,
                            'sequence' => $scoreProperty->sequence,
                            'candidate_score_id' => $scoreProperty->candidate_score_id
                        );
                        array_push($candidateScoreForm, $element);

                    }
                }
            } else {

                $element = (object)array(
                    'register_id' => $mergedObject->candidateProperties->register_id,
                    'course_name' => $mergedObject->candidateProperties->course_name,
                    'total_question' => $mergedObject->candidateProperties->total_question,
                    'candidate_id' => $mergedObject->candidateProperties->candidate_id,
                    'score_c' => '',
                    'score_w' => '',
                    'score_na' => '',
                    'sequence' => '',
                    'candidate_score_id' => ''
                );
                array_push($candidateScoreForm, $element);
            }
        }

//        dd($candidateScoreForm);
        return $candidateScoreForm;
    }

    private function sortObject($a, $b)
    {
        return $a->candidateProperties->register_id - $b->candidateProperties->register_id;
    }

    public function insertCandidateScore($exam_id, $requestDatas) {


        $numberCorrection = $requestDatas['candidate_score_id_1']['number_correction'];

        if($numberCorrection == '1') {

            $result = $this->checkEachCandidateScoreId($requestDatas, $numberCorrection );

            return $result;

        } else if($numberCorrection == '2') {

            $result = $this->checkEachCandidateScoreId($requestDatas, $numberCorrection);

            return $result;

        } else {
            return 'false';
        }
    }

    private function checkEachCandidateScoreId ($requestDatas, $numberCorrection) {

        $empty = '';
        $status = 0;
        for($i = 1; $i <= count($requestDatas)-1; $i++) {

            $eachCandidateScore = $requestDatas['candidate_score_id_'.$i]['score_id'];

            $candidateId = $requestDatas['candidate_score_id_'.$i]['candidate_id'];
            $subjectId = $requestDatas['candidate_score_id_'.$i]['subject_id'];
            $correctAns = $requestDatas['candidate_score_id_'.$i]['correct'];
            $wrongAns = $requestDatas['candidate_score_id_'.$i]['wrong'];
            $noAns = $requestDatas['candidate_score_id_'.$i]['na'];

            if($eachCandidateScore == $empty) {
                if($correctAns != null || $wrongAns != null  || $noAns !=null ) {
                    $insertResult = $this->storeCandidateScore($subjectId, $candidateId, $correctAns, $wrongAns, $noAns, $numberCorrection, $i);

                    if($insertResult) {
                        $status++;
                    }
                }

            } else {

                $updateResult = $this->updateCandidateScore($subjectId, $candidateId, $correctAns, $wrongAns, $noAns, $numberCorrection, $i);
                if($updateResult) {
                    $status++;
                }
            }
        }

        if($status !==0) {
            return ['status'=>true];
        } else {
            return ['status'=>false];
        }

    }

    private function storeCandidateScore($subjectId, $candidateId, $correctAns, $wrongAns, $noAns, $numberCorrection, $orderInRoom) {

        $insertedVal = DB::table('candidateEntranceExamScores')->insertGetId([
            'candidate_id' => $candidateId,
            'score_c' => (int)$correctAns,
            'score_w' => (int)$wrongAns,
            'score_na' => (int)$noAns,
            'sequence' => (int)$numberCorrection,
            'entrance_exam_course_id' => (int)$subjectId,
            'candidate_number_in_room' => $orderInRoom
        ]);

        return $insertedVal;

    }

    private function updateCandidateScore($subjectId, $candidateId, $correctAns, $wrongAns, $noAns, $numberCorrection) {

        $updateRecord = DB::table('candidateEntranceExamScores')
            ->where([
                ['entrance_exam_course_id', '=', $subjectId],
                ['candidate_id', '=', $candidateId],
                ['sequence', '=', $numberCorrection ]
            ])
            ->update(array(
                'score_c' => (int)$correctAns,
                'score_w' => (int)$wrongAns,
                'score_na' => (int)$noAns,
            ));

        return $updateRecord;
    }

    public function addNewCorrectionCandidateScore($examId, StoreEntranceExamScoreRequest $request) {

//        dd($request->score_c.'--'.$request->score_w.'--'.$request->score_na.'--'.$request->sequence.'--'.$request->course_id.'--'.$request->order.'--'.$request->candidate_id );

        $checkSequenCandidateId = DB::table('candidateEntranceExamScores')
                                    ->where([
                                        ['candidate_id', '=', $request->candidate_id],
                                        ['sequence', '=', $request->sequence ],
                                        ['entrance_exam_course_id', '=', $request->course_id]

                                    ])->select('id')->get();
        if($checkSequenCandidateId) {
            return (['status' => false]);
        } else {

            if($request->score_c + $request->score_w + $request->score_na == 0 ) {

                return (['status' => false]);
            } else {
                $insertedVal = DB::table('candidateEntranceExamScores')->insertGetId([
                    'candidate_id' => $request->candidate_id,
                    'score_c' => (int)$request->score_c,
                    'score_w' => (int)$request->score_w,
                    'score_na' => (int)$request->score_na,
                    'sequence' => (int)$request->sequence,
                    'entrance_exam_course_id' => (int)$request->course_id,
                    'candidate_number_in_room' => $request->order
                ]);

                return $insertedVal;

            }

        }
    }

    public function reportErrorCandidateExamScores($examId, $courseId) {

        $errorCandidateScores = [];
        $errors =[];
        $candidateIds = DB::table('candidates')
            ->join('exams', 'exams.id', '=', 'candidates.exam_id')
            ->join('exam_room', 'exam_room.room_id', '=', 'candidates.room_id')
            ->join('rooms', 'rooms.id', '=', 'candidates.room_id')
            ->where('exams.id', '=', $examId)
            ->select('candidates.id as candidate_id', 'register_id', 'exam_room.roomcode as room_code', 'rooms.id as room_id')
            ->orderBy('register_id', 'ASC')
            ->get();

        foreach ($candidateIds as $candidateId) {
            $statusCorrectScore = 0 ;
            $statusWrongScore = 0;
            $cadidateScores = DB::table('candidateEntranceExamScores')
                ->join('entranceExamCourses', 'entranceExamCourses.id', '=', 'candidateEntranceExamScores.entrance_exam_course_id')
                ->where([
                    ['candidate_id', $candidateId->candidate_id],
                    ['candidateEntranceExamScores.entrance_exam_course_id', '=', $courseId]
                ])
                ->select('entranceExamCourses.total_question', 'entranceExamCourses.id as course_id', 'candidateEntranceExamScores.id', 'candidate_number_in_room', 'entrance_exam_course_id', 'candidateEntranceExamScores.score_c', 'candidateEntranceExamScores.score_w', 'candidateEntranceExamScores.score_na', 'candidateEntranceExamScores.sequence')
                ->orderBy('sequence', 'ASC')
                ->get();

//            dd($cadidateScores);

            array_push($errors, (object) array('candidateProperties' => $candidateId, 'scoreErrors' => $cadidateScores) );

            if ($cadidateScores) {
                $length = count($cadidateScores);

                for ($i = 0; $i < $length ; $i++) {
                    $tmpScoreCorrect = $cadidateScores[$i]->score_c;
                    $tmpScoreWrong = $cadidateScores[$i]->score_w;
                    $tmpScoreNA = $cadidateScores[$i]->score_na;
                    $tmpTotalQuestion = $cadidateScores[$i]->total_question;

                    for ($j = $length-1; $j > $i ; $j--) {

                        if ($tmpScoreCorrect == $cadidateScores[$j]->score_c && $tmpScoreWrong == $cadidateScores[$j]->score_w && $tmpScoreNA == $cadidateScores[$j]->score_na) {

                            if( ($tmpScoreCorrect + $tmpScoreWrong + $tmpScoreNA) == $tmpTotalQuestion && $cadidateScores[$j]->score_c + $cadidateScores[$j]->score_w + $cadidateScores[$j]->score_na == $cadidateScores[$j]->total_question ||
                                ($tmpScoreCorrect + $tmpScoreWrong + $tmpScoreNA) == 0 && $cadidateScores[$j]->score_c + $cadidateScores[$j]->score_w + $cadidateScores[$j]->score_na == 0) {

                                $statusCorrectScore++;

                                DB::table('candidateEntranceExamScores')
                                    ->where([
                                        ['entrance_exam_course_id', '=', $cadidateScores[$j]->entrance_exam_course_id],
                                        ['candidate_id', '=', $candidateId->candidate_id],
                                        ['sequence', '=', $cadidateScores[$j]->sequence ]
                                    ])
                                    ->update(array(
                                        'is_completed' => true
                                    ));
                            } else {
                                $statusWrongScore++;
                            }
                        } else {
                            $statusWrongScore++;
                        }
                        if( $statusCorrectScore == 0 &&  $statusWrongScore == ($length*($length-1))/2 ) {
                            array_push($errorCandidateScores, (object) array('candidateProperties' => $candidateId, 'scoreErrors' => $cadidateScores) );
                        }

                    }
                }

            }
        }
        dd($errorCandidateScores);
        return $errorCandidateScores;

    }

    public function calculationCandidateScores() {


    }

}
































