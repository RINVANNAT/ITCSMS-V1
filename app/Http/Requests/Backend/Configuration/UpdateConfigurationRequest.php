<?php

namespace App\Http\Requests\Backend\Configuration;

use App\Http\Requests\Request;

/**
 * Class UpdateConfigurationRequest
 * @package App\Http\Requests\Backend\Configuration
 */
class UpdateConfigurationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-configuration');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'key' => 'required',
            'value' => 'required'
        ];
    }
}
