<?php

namespace App\Http\Requests\Backend\Configuration\Account;

use App\Http\Requests\Request;

/**
 * Class CreateAccountRequest
 * @package App\Http\Requests\Backend\Configuration\Account
 */
class CreateAccountRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-accounts');
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
