<?php

namespace App\Http\Controllers\API;

use App\Utils\FormParamManager;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Form;

class DepartmentOptionAPIController extends Controller
{


    public function getAll() {
        $options = DB::table('departmentOptions')->get();
        return $options;
    }

    public function getOptionByDeptId(Request $request) {

        $params = FormParamManager::getFormParams($request);
        $options = DB::table('departmentOptions')->where('department_id', $params['department_id'])->get();
        return $options;

    }

    public function unique(Request $request) {

        $params = FormParamManager::getFormParams($request);
        $option = DB::table('departmentOptions')->where('id', $params['department_option_id'])->first();
        return (array)$option;
    }
}
