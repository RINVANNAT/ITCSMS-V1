<?php

namespace App\Http\Requests\Backend\Reporting;

use App\Http\Requests\Request;

/**
 * Class StoreReportingRequest
 * @package App\Http\Requests\Backend\Reporting
 */
class StoreReportingRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-reporting');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'description' => 'max:255',
        ];
    }
}
