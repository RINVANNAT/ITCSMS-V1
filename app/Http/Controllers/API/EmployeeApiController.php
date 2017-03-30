<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\API\EmployeeApiRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EmployeeApiController extends Controller
{


    public function employeeDataFromDB(EmployeeApiRequest $request) {


        $employees = DB::table('employees')
            ->get();

        return $employees;
    }
}
