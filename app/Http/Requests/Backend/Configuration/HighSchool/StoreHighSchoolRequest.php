<?php

namespace App\Http\Requests\Backend\Configuration\HighSchool;

use App\Http\Requests\Request;

/**
 * Class StoreHighSchoolRequest
 * @package App\Http\Requests\Backend\Configuration\HighSchool
 */
class StoreHighSchoolRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-highSchools');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required',
            'name_en' => 'required',
        ];
    }
}
