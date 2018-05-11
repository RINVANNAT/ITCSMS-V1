<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Backend\Traits\FilteringTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Response;

class FilterController extends Controller
{
    use FilteringTrait;

    public function get_filter_value_by_class(Request $request){
        $filters = $this->get_available_class($request->get('academic_year_id'));

        $select2_object = [];
        foreach($filters as $department => $filter){
            $group = [
                'text' => $department,
                'children' => []
                ];
            foreach($filter as $class=>$attributes){
                $group['children'][] =
                        [
                            'id' => $attributes["degree_id"]."_".$attributes["grade_id"]."_".$attributes['department_id']."_".$attributes["department_option_id"],
                            'department_option_id'=>$attributes["department_option_id"],
                            'department_id'=>$attributes['department_id'],
                            'degree_id'=>$attributes["degree_id"],
                            'grade_id'=>$attributes["grade_id"],
                            'text' => $class
                        ];
            }
            $select2_object['data'][] = $group;
        }
        $select2_object['status'] = 'success';
        return Response::json($select2_object);
    }

    public function get_filter_by_class_final_year(Request $request){
        $filters = $this->get_available_class_last_year($request->get('academic_year_id'));

        $select2_object = [];
        foreach($filters as $department => $filter){
            $group = [
                'text' => $department,
                'children' => []
            ];
            foreach($filter as $class=>$attributes){
                $group['children'][] =
                    [
                        'id' => $attributes["degree_id"]."_".$attributes["grade_id"]."_".$attributes['department_id']."_".$attributes["department_option_id"],
                        'department_option_id'=>$attributes["department_option_id"],
                        'department_id'=>$attributes['department_id'],
                        'degree_id'=>$attributes["degree_id"],
                        'grade_id'=>$attributes["grade_id"],
                        'text' => $class
                    ];
            }
            $select2_object['data'][] = $group;
        }
        $select2_object['status'] = 'success';
        return Response::json($select2_object);
    }

    public function get_filter_value_by_group(Request $request){

    }
}
