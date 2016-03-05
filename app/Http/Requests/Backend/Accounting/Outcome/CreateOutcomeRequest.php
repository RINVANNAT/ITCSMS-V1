<?php

namespace App\Http\Requests\Backend\Accounting\Outcome;

use App\Http\Requests\Request;

/**
 * Class CreateOutcomeRequest
 * @package App\Http\Requests\Backend\Accounting\Outcome
 */
class CreateOutcomeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-outcomes');
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
