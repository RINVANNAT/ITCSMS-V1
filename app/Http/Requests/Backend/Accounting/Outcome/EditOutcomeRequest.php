<?php

namespace App\Http\Requests\Backend\Accounting\Outcome;

use App\Http\Requests\Request;

/**
 * Class EditOutcomeRequest
 * @package App\Http\Requests\Backend\Accounting\Outcome
 */
class EditOutcomeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-outcomes');
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
