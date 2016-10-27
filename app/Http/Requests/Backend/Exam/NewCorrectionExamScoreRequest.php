<?php

namespace App\Http\Requests\Backend\Exam;

use App\Http\Requests\Request;

/**
 * Class StoreEntranceExamScoreRequest
 * @package App\Http\Requests\Backend\Exam
 */
class NewCorrectionExamScoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('view-entrance-exam-course-score');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [];
        $rules['corrector_name'] = 'required';

        $rules['serializ_data'] = 'required';
        $check =0;
        $requestScore = $this->serializ_data;
        for($index=0; $index < count($requestScore); $index++) {
            parse_str($requestScore[$index], $outPut);

            if(($outPut['course_id'][0] != null)
                && ($outPut['score_c'][0] !=null)
                && ($outPut['score_w'][0] !=null)
                && ($outPut['score_na'][0] !=null)
                && ($outPut['sequence'][0] !=null)
                && ($outPut['order'][0] !=null)
                && ($outPut['roomcode'][0] !=null)) {
                $check++;
            }
        }


        if($check ==count($requestScore)) {
            return $rules;
        } else {
            $rules['required'] = 'required';
            return $rules;
        }

    }
}
