<?php

namespace App\Http\Requests\Backend\Accounting\Customer;

use App\Http\Requests\Request;

/**
 * Class CreateCustomerRequest
 * @package App\Http\Requests\Backend\Accounting\Customer
 */
class CreateCustomerRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-customers');
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
