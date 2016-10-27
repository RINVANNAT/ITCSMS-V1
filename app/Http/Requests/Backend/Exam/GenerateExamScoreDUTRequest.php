<?php

namespace App\Http\Requests\Backend\Exam;

use App\Http\Requests\Request;

/**
 * Class StoreEntranceExamScoreRequest
 * @package App\Http\Requests\Backend\Exam
 */
class GenerateExamScoreDUTRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('get-candidate-result-score-dut');//generate student DUT score
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [];

        return $rules;

    }
}
