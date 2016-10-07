<?php

namespace App\Repositories\Backend\Exam;


use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Exam\StoreEntranceExamScoreRequest;
use App\Models\Exam;
use App\Models\UserLog;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response;
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

        // Examination start - end
        $date_start_end = explode(" - ",$input['date_start_end']);
        $date_start = $date_start_end[0];
        $date_end = $date_start_end[1];

        // Success registration start - end
        $success_registration_date_start_end = explode(" - ",$input['success_registration_date_start_end']);
        $success_registration_date_start = Carbon::createFromFormat('d/m/Y',$success_registration_date_start_end[0])->format('Y-m-d');
        $success_registration_date_end = Carbon::createFromFormat('d/m/Y',$success_registration_date_start_end[1])->format('Y-m-d');

        // Reserve registration start - end
        $reserve_registration_date_start_end = explode(" - ",$input['reserve_registration_date_start_end']);
        $reserve_registration_date_start = Carbon::createFromFormat('d/m/Y',$reserve_registration_date_start_end[0])->format('Y-m-d');
        $reserve_registration_date_end = Carbon::createFromFormat('d/m/Y',$reserve_registration_date_start_end[1])->format('Y-m-d');

        $exam = new Exam();

        $exam->name = $input['name'];
        $exam->date_start = $date_start;
        $exam->date_end = $date_end;
        $exam->success_registration_start = $success_registration_date_start;
        $exam->success_registration_stop = $success_registration_date_end;
        $exam->reserve_registration_start = $reserve_registration_date_start;
        $exam->reserve_registration_stop = $reserve_registration_date_end;

        $exam->active = true;
        $exam->description = $input['description'];
        $exam->academic_year_id = $input['academic_year_id'];
        $exam->type_id = $input['type_id'];

        $exam->created_at = Carbon::now();
        $exam->create_uid = auth()->id();

        if ($exam->save()) {
            UserLog::log([
                'model' => 'Exam',
                'action'=> 'Create',
                'data'  => $exam->id, // if it is create action, store only the new id.
            ]);
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
        $old_record = json_encode($exam);

        $date_start_end = explode(" - ",$input['date_start_end']);
        $date_start = $date_start_end[0];
        $date_end = $date_start_end[1];

        // Success registration start - end
        $success_registration_date_start_end = explode(" - ",$input['success_registration_date_start_end']);
        $success_registration_date_start = Carbon::createFromFormat('d/m/Y',$success_registration_date_start_end[0])->format('Y-m-d');
        $success_registration_date_end = Carbon::createFromFormat('d/m/Y',$success_registration_date_start_end[1])->format('Y-m-d');

        // Reserve registration start - end
        $reserve_registration_date_start_end = explode(" - ",$input['reserve_registration_date_start_end']);
        $reserve_registration_date_start = Carbon::createFromFormat('d/m/Y',$reserve_registration_date_start_end[0])->format('Y-m-d');
        $reserve_registration_date_end = Carbon::createFromFormat('d/m/Y',$reserve_registration_date_start_end[1])->format('Y-m-d');

        $exam->name = $input['name'];
        $exam->date_start = $date_start;
        $exam->date_end = $date_end;
        $exam->success_registration_start = $success_registration_date_start;
        $exam->success_registration_stop = $success_registration_date_end;
        $exam->reserve_registration_start = $reserve_registration_date_start;
        $exam->reserve_registration_stop = $reserve_registration_date_end;
        $exam->active = isset($input['active'])?true:false;
        $exam->description = $input['description'];
        $exam->academic_year_id = $input['academic_year_id'];
        $exam->type_id = $input['type_id'];

        $exam->updated_at = Carbon::now();
        $exam->write_uid = auth()->id();

        if ($exam->save()) {
            UserLog::log([
                'model' => 'Exam',
                'action'=> 'Update',
                'data'  => $old_record, // store all old record so we can role back
            ]);
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

        $exam->active = false; // Instead of real delete, just change active to false.
        $exam->updated_at = Carbon::now();
        $exam->write_uid = auth()->id();

        //Don't delete the role is there are users associated
//        if ($exam->candidates()->count() > 0) {
//            throw new GeneralException(trans('exceptions.backend.exams.has_candidate'));
//        }
//
//        $exam->rooms()->sync([]);
//        $exam->employees()->sync([]);
//        $exam->students()->sync([]);

        if ($exam->save()) {
            UserLog::log([
                'model' => 'Exam',
                'action'=> 'Delete',
                'data'  => $id, // Store only id because we didn't really delete the record
            ]);
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }

    public function requestInputScoreForm ($exam_id, $request, $number_correction) {

        $mergedObjects = [];
        $candidateScoreForm = [];

        if($request->room_id != 'undefined') {

            $candidates = DB::table('candidates')
                ->leftJoin('entranceExamCourses', 'entranceExamCourses.exam_id', '=', 'candidates.exam_id')
                ->where([
                    ['candidates.room_id', '=', $request->room_id],
                    ['candidates.active', '=', true],
                    ['candidates.exam_id', '=', $exam_id],
                    ['entranceExamCourses.id', '=', $request->entrance_course_id],
                ])

                ->select( 'candidates.id as candidate_id', 'candidates.register_id', 'entranceExamCourses.name_kh as course_name','entranceExamCourses.total_question')
                ->orderBy('candidates.register_id', 'ASC')->get();

            if($candidates) {
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
                return $candidateScoreForm;
            }

        }
        return $candidateScoreForm;

    }

    private function sortObject($a, $b)
    {
        return $a->candidateProperties->register_id - $b->candidateProperties->register_id;
    }

    public function insertCandidateScore($exam_id, $requestDatas, $correctorName) {

        $check =0;
        $numberCorrection = $requestDatas['sequence'];
        $roomcode = $requestDatas['roomcode'];
        $subjectId = $requestDatas['course_id'];

        $correctAns = $requestDatas['score_c'];
        $wrongAns = $requestDatas['score_w'];
        $noAns = $requestDatas['score_na'];

        //dd($requestDatas);
        for($i = 0; $i < count($requestDatas['score_c']); $i++) {
            $insertResult = $this->storeCandidateScore($subjectId, $correctAns[$i], $wrongAns[$i], $noAns[$i], $numberCorrection, $i+1, $correctorName,$roomcode,$exam_id);
            if($insertResult) {
                $check++;
            }
        }

        if($check == count($requestDatas['score_c'])) {
            return ['status'=>true];
        } else {
            return ['status'=>false];
        }
    }

    /*private function checkEachCandidateScoreId ($requestDatas, $numberCorrection, $correctorName) {

        $empty = '';
        $status = 0;

        if(count($requestDatas['score_c']) === count($requestDatas['score_w']) && count($requestDatas['score_w'])==count($requestDatas['score_na'])) {

            $eachCandidateScore = $requestDatas['score_id'];
            $candidateId = $requestDatas['candidate_id'];
            $subjectId = $requestDatas['course_id'];
            $correctAns = $requestDatas['score_c'];
            $wrongAns = $requestDatas['score_w'];
            $noAns = $requestDatas['score_na'];

            for($i = 0; $i < count($requestDatas['score_c']); $i++) {

                if($eachCandidateScore[$i] == $empty) {
                    if($correctAns != null || $wrongAns != null  || $noAns !=null ) {


                        $insertResult = $this->storeCandidateScore($subjectId[$i], $candidateId[$i], $correctAns[$i], $wrongAns[$i], $noAns[$i], $numberCorrection, $i+1, $correctorName);

                        if($insertResult) {
                            $status++;
                        }
                    }

                } else {

                    $updateResult = $this->updateCandidateScore($subjectId[$i], $candidateId[$i], $correctAns[$i], $wrongAns[$i], $noAns[$i], $numberCorrection, $i+1, $correctorName);
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


        return ['status'=>false];
    }*/

    private function getCandidateScore($roomcode, $subjectId, $sequence, $orderInRoom, $exam_id) {


       $CandidateScore =  DB::table('secret_room_score')
                            ->where([
                                ['sequence', $sequence],
                                ['course_id', $subjectId],
                                ['roomcode', $roomcode],
                                ['order_in_room', $orderInRoom],
                                ['exam_id', $exam_id]
                            ])
                            ->first();

        return $CandidateScore;

    }

    private function storeCandidateScore($subjectId, $correctAns, $wrongAns, $noAns, $numberCorrection, $orderInRoom, $correctorName, $roomcode,$exam_id)
    {


        if($this->getCandidateScore($roomcode,$subjectId,$numberCorrection,$orderInRoom,$exam_id)){
            return false;
        } else {
            $insertedVal = DB::table('secret_room_score')->insertGetId([
                'score_c' => (int)$correctAns,
                'score_w' => (int)$wrongAns,
                'score_na' => (int)$noAns,
                'sequence' => (int)$numberCorrection,
                'course_id' => (int)$subjectId,
                'corrector_name' => $correctorName,
                'order_in_room' => $orderInRoom,
                'roomcode' => $roomcode,
                'exam_id' => $exam_id
            ]);
            //UserLog
            $this->getUserLog($insertedVal, $model = 'SecretRoomScore', $action = 'Create');
            return $insertedVal;
        }

    }

    public function getUserLog($data, $model, $action) {
        $storeData = json_encode($data);
        UserLog::log([
            'model' => $model,
            'action'   => $action, // Import, Create, Delete, Update
            'data'     => $storeData // if it is create action, store only the new id.
        ]);

    }


    private function updateCandidateScore($subjectId, $candidateId, $correctAns, $wrongAns, $noAns, $numberCorrection, $orderInRoom, $correctorName) {

        $previousRecord = $this->getCandidateScore($candidateId, $subjectId, $numberCorrection);

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
                'corrector' => $correctorName,
                'candidate_number_in_room' => $orderInRoom
            ));

        if($updateRecord) {
            //UserLog
            $this->getUserLog($previousRecord,$model ='CandidateEntranceExamScores', $action = 'Update');
        }
        return $updateRecord;
    }

    public function addNewCorrectionCandidateScore($examId,  $request, $correctorName) {
        //StoreEntranceExamScoreRequest


        $requestScore = $request->serializ_data;
        $check =0;

        for($index=0; $index < count($requestScore); $index++) {
            parse_str($requestScore[$index], $outPut);// convertion of the serialized data to array json
            $checkSequenCandidateId = DB::table('candidateEntranceExamScores')
                ->where([
                    ['candidate_id', '=', $outPut['candidate_id'][0]],
                    ['sequence', '=', $outPut['sequence'][0] ],
                    ['entrance_exam_course_id', '=', $outPut['course_id'][0]]

                ])->select('id')->get();
            if($checkSequenCandidateId) {
                return (['status' => false]);
            } else {

                $res = $this->storeCandidateScore($outPut['course_id'][0], $outPut['candidate_id'][0], $outPut['score_c'][0], $outPut['score_w'][0], $outPut['score_na'][0], $outPut['sequence'][0], $outPut['order'][0], $correctorName);
                if($res) {
                    $check++;
                }

            }
        }

        if($check == count($requestScore)) {
            return (['status' => true]);
        }


//        dd($request->score_c.'--'.$request->score_w.'--'.$request->score_na.'--'.$request->sequence.'--'.$request->course_id.'--'.$request->order.'--'.$request->candidate_id );

    }

    public function reportErrorCandidateExamScores($examId, $courseId) {

        $errorCandidateScores = [];
        $errors =[];
        $candidateIds = DB::table('candidates')
            ->join('exams', 'exams.id', '=', 'candidates.exam_id')
            ->join('examRooms', 'examRooms.id', '=', 'candidates.room_id')
            ->where([
                ['exams.id', '=', $examId],
                ['candidates.active', '=', true]
            ])
            ->whereNotNull('examRooms.roomcode')
            ->select('candidates.id as candidate_id', 'register_id', 'examRooms.roomcode as room_code', 'examRooms.id as room_id')
            ->orderBy('register_id', 'ASC')
            ->get();

        if($candidateIds) {
            foreach ($candidateIds as &$candidateId) {

                $candidateId->room_code = Crypt::decrypt($candidateId->room_code);
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

                //array_push($errors, (object) array('candidateProperties' => $candidateId, 'scoreErrors' => $cadidateScores) );

                if ($cadidateScores) {
                    $length = count($cadidateScores);

                    if($length <2 ){
                        array_push($errorCandidateScores, (object) array('candidateProperties' => $candidateId, 'scoreErrors' => $cadidateScores) );
                    } else {
                        for ($i = 0; $i < $length ; $i++) {
                            // Select up down
                            $tmpScoreCorrect = $cadidateScores[$i]->score_c;
                            $tmpScoreWrong = $cadidateScores[$i]->score_w;
                            $tmpScoreNA = $cadidateScores[$i]->score_na;
                            //$tmpTotalQuestion  = $cadidateScores[$i]->total_question;

                            // Compare bottom up
                            for ($j = $length-1; $j > $i ; $j--) {

                                // Check if there any any error in candidate's score
                                if ($tmpScoreCorrect == $cadidateScores[$j]->score_c && $tmpScoreWrong == $cadidateScores[$j]->score_w && $tmpScoreNA == $cadidateScores[$j]->score_na) {



                                    if( ($cadidateScores[$j]->score_c + $cadidateScores[$j]->score_w + $cadidateScores[$j]->score_na == $cadidateScores[$j]->total_question) || ($cadidateScores[$j]->score_c + $cadidateScores[$j]->score_w + $cadidateScores[$j]->score_na == 0)) {


                                        $statusCorrectScore++; // every score is equal and total question is correct

                                        $previousRecord = $this->getCandidateScore($candidateId->candidate_id, $cadidateScores[$j]->entrance_exam_course_id, $cadidateScores[$j]->sequence);

                                        $res = DB::table('candidateEntranceExamScores')
                                                ->where([
                                                    ['entrance_exam_course_id', '=', $cadidateScores[$j]->entrance_exam_course_id],
                                                    ['candidate_id', '=', $candidateId->candidate_id],
                                                    ['sequence', '=', $cadidateScores[$j]->sequence ]
                                                ])
                                                ->update(array(
                                                    'is_completed' => true
                                                ));
                                        if($res) {
                                            //UserLog
                                            $this->getUserLog($previousRecord,$model ='CandidateEntranceExamScores', $action = 'Update');
                                        }

                                    } else {
                                        $statusWrongScore++;
                                    }
                                } else {
                                    $statusWrongScore++;
                                }

                                // If there is an error, store it to $errorCandidateScores
                                if( $statusCorrectScore == 0 && $statusWrongScore == ($length*($length-1))/2 ) { //
                                    array_push($errorCandidateScores, (object) array('candidateProperties' => $candidateId, 'scoreErrors' => $cadidateScores) );
                                }
                            }
                        }
                    }


                }
            }


            if($errorCandidateScores) {

                $status = true;
                $this->deleteStatusCandidateScores($examId, $courseId);
                $this->insertStatusCandidateScores($examId, $courseId, $status);
            } else {
                $status = false;
                $this->deleteStatusCandidateScores($examId, $courseId);
                $this->insertStatusCandidateScores($examId, $courseId, $status);
            }

            return $errorCandidateScores;
        }
    }


    private function insertStatusCandidateScores($examId, $courseId, $status) {

        $res = DB::table('statusCandidateScores')->insert(
            ['exam_id' => $examId, 'status' => $status, 'entrance_exam_course_id' =>$courseId ]
        );

        return $res;
    }

    private function deleteStatusCandidateScores($examId, $courseId) {

        DB::table('statusCandidateScores')->where([
            ['entrance_exam_course_id', '=', $courseId],
            ['exam_id', '=', $examId]
        ])->delete();

    }


}
































