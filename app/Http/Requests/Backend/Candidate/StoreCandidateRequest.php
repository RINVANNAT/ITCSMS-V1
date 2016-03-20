<?php

namespace App\Http\Requests\Backend\Candidate;

use App\Http\Requests\Request;

/**
 * Class StoreCandidateRequest
 * @package App\Http\Requests\Backend\Candidate
 */
class StoreCandidateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-candidates');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_kh' => 'required',
            'name_latin' => 'required',
            'pob' => 'required',
            'dob' => 'required',
            'gender_id' => 'required',
            'register_from' => 'required',
            'highschool_id' => 'required',
            'bac_percentile' => 'required',
            'bac_total_grade' => 'required',
            'bac_year' => 'required',
            'promotion_id' => 'required'
        ];
    }
}
