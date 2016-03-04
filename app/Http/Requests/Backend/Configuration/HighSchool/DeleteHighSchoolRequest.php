<?php

namespace App\Http\Requests\Backend\Configuration\HighSchool;

use App\Http\Requests\Request;

/**
 * Class DeleteHighSchoolRequest
 * @package App\Http\Requests\Backend\Configuration\HighSchool
 */
class DeleteHighSchoolRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
