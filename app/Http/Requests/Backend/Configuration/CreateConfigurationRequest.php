<?php

namespace App\Http\Requests\Backend\Configuration;

use App\Http\Requests\Request;

/**
 * Class CreateConfigurationRequest
 * @package App\Http\Requests\Backend\Configuration
 */
class CreateConfigurationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-configuration');
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
