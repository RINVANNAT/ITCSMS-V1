<?php

namespace App\Http\Requests\Backend\Exam;

use App\Http\Requests\Request;

/**
 * Class StoreEntranceExamScoreRequest
 * @package App\Http\Requests\Backend\Exam
 */
class StoreEntranceExamScoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-entrance-exam-score');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [];

        //dd($this->request->get('candidate_id'));
        foreach($this->request->get('candidate_id') as $index => $val)
        {
            $rules['candidate_id.' . $index] = 'required';
            $rules['course_id.' . $index] = 'required';
            $rules['score_c.' . $index] = 'required|max:2';
            $rules['score_w.' . $index] = 'required|max:2';
            $rules['score_na.' . $index] = 'required|max:2';
        }

        //$rules['sequence'] = 'required|unique_with:candidateEntranceExamScores,candidate_id,entrance_exam_course_id';


        return $rules;

//        return [
//            'candidate_id' => 'required',
//            'course_id' => 'required',
//            'score_c' => 'required',
//            'score_w' => 'required',
//            'score_na' => 'required',
//            'order' => 'required',
//            'sequence' => 'required|unique_with:candidateEntranceExamScores,candidate_id,entrance_exam_course_id',
//
//        ];
    }
}
