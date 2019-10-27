<?php

namespace App\Http\Requests\Backend\Student;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class StoreRoleRequest
 * @package App\Http\Requests\Backend\Access\Role
 */
class ImportStudentT2ToI3Request extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('view-access-management');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'import' => 'required'
        ];
    }
}
