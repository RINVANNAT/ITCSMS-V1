<?php

namespace App\Http\Requests\Backend\Configuration\OutcomeType;

use App\Http\Requests\Request;

/**
 * Class CreateOutcomeTypeRequest
 * @package App\Http\Requests\Backend\Configuration\OutcomeType
 */
class CreateOutcomeTypeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-outcomeTypes');
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
