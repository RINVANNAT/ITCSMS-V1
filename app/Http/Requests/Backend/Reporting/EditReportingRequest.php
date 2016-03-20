<?php

namespace App\Http\Requests\Backend\Reporting;

use App\Http\Requests\Request;

/**
 * Class EditReportingRequest
 * @package App\Http\Requests\Backend\Reporting
 */
class EditReportingRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-reporting');
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
