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

        $rules['course_id'] = 'required';
        $rules['roomcode'] = 'required';
        $rules['roomcode'] = 'required';
        $rules['corrector_name'] = 'required';

        foreach($this->request->get('candidate_id') as $index => $val)
        {
            $rules['score_c.' . $index] = 'required|max:3';
            $rules['score_w.' . $index] = 'required|max:3';
            $rules['score_na.' . $index] = 'required|max:3';
        }


        return $rules;
    }
}
