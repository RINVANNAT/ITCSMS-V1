<?php

namespace App\Http\Requests\Backend\Candidate;

use App\Http\Requests\Request;

/**
 * Class UpdateCandidateRequest
 * @package App\Http\Requests\Backend\Candidate
 */
class UpdateCandidateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-exam-candidate');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules =  [
            'name_kh' => 'required',
            'name_latin' => 'required',
            'pob' => 'required',
            'dob' => 'required',
            'gender_id' => 'required',
            'register_from' => 'required',
            'bac_percentile' => 'required',
            'bac_total_grade' => 'required',
            'bac_year' => 'required',
            'promotion_id' => 'required',
            'exam_id' => 'required',
            'register_id' => 'integer|required',
        ];

        if($this->request->get('choice_department') != null){
            foreach($this->request->get('choice_department') as $index => $val)
            {
                $rules['choice_department.'.$index] = 'required';
            }
        }

        return $rules;
    }
}
