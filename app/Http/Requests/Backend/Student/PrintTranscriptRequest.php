<?php

namespace App\Http\Requests\Backend\Student;

use App\Http\Requests\Request;

/**
 * Class PrintTranscriptRequest
 * @package App\Http\Requests\Backend\Student
 */
class PrintTranscriptRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('print-transcript');
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
