<?php

namespace App\Http\Requests\Backend\Candidate;

use App\Http\Requests\Request;

/**
 * Class GenerateRoomExamRequest
 * @package App\Http\Requests\Backend\Candidate
 */
class GenerateRoomExamRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('generate-room-exam-candidate');
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
