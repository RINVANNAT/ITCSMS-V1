<?php

namespace App\Http\Requests\Backend\Configuration\Redouble;

use App\Http\Requests\Request;

/**
 * Class DeleteRedoubleRequest
 * @package App\Http\Requests\Backend\Configuration\Redouble
 */
class DeleteRedoubleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-redoubles');
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
