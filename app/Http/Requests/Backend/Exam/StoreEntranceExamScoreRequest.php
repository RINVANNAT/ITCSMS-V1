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
        return [
            'candidate_id' => 'required',
            'course_id' => 'required',
            'score_c' => 'integer',
            'score_w' => 'integer',
            'score_na' => 'integer',
            'sequence' => 'required|unique_with:candidateEntranceExamScores,candidate_id,course_id',

        ];
    }
}
