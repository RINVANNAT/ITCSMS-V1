<?php

namespace App\Http\Requests\Backend\Configuration\Degree;

use App\Http\Requests\Request;

/**
 * Class EditDegreeRequest
 * @package App\Http\Requests\Backend\Configuration\Degree
 */
class EditDegreeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-degrees');
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
