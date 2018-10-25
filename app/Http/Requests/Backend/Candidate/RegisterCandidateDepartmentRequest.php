<?php

namespace App\Http\Requests\Backend\Candidate;

use App\Http\Requests\Request;

/**
 * Class RegisterCandidateRequest
 * @package App\Http\Requests\Backend\Candidate
 */
class RegisterCandidateDepartmentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('register-candidate-department');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }
}
