<?php

namespace App\Http\Requests\Backend\Configuration;

use App\Http\Requests\Request;

/**
 * Class DeleteConfigurationRequest
 * @package App\Http\Requests\Backend\Configuration
 */
class DeleteConfigurationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-configuration');
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
