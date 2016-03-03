<?php

namespace App\Http\Requests\Backend\Configuration\Grade;

use App\Http\Requests\Request;

/**
 * Class CreateGradeRequest
 * @package App\Http\Requests\Backend\Configuration\Grade
 */
class CreateGradeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-degrees');
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
