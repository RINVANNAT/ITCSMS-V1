<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DefineAverage;
use Illuminate\Http\Request;

class DefineAverageController extends Controller
{
    public function getAverage(Request $request)
    {
        $this->validate($request, [
            'academic_year_id' => 'required|numeric',
            'department_id' => 'required|numeric',
            'degree_id' => 'required|numeric',
            'grade_id' => 'required|numeric'
        ]);

        try {

            if ($request->semester_id == '') {
                $request['semester_id'] = 0;
            }

            if ($request->option_id == '') {
                $request['option_id'] = null;
            }

            $average = DefineAverage::where([
                'academic_year_id' => $request->academic_year_id,
                'department_id' => $request->department_id,
                'option_id' => $request->option_id,
                'semester_id' => $request->semester_id,
                'degree_id' => $request->degree_id,
                'grade_id' => $request->grade_id
            ])->first();

            return message_success($average);

        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function storeAverage(Request $request)
    {
        $this->validate($request, [
            'academic_year_id' => 'required|numeric',
            'department_id' => 'required|numeric',
            'value' => 'required|numeric'
        ]);

        try {
            $semester_id = $request->semester_id;
            if ($request->semester_id == '') {
                $semester_id = 0;
                $request['semester_id'] = 0;
            }

            $option_id = $request->option_id;
            if ($request->option_id == '') {
                $option_id = null;
                $request['option_id'] = null;
            }

            $average = DefineAverage::where([
                'academic_year_id' => $request->academic_year_id,
                'department_id' => $request->department_id,
                'semester_id' => $semester_id,
                'option_id' => $option_id,
                'grade_id' => $request->grade_id,
                'degree_id' => $request->degree_id
            ])->first();

            if ($average instanceof DefineAverage) {
                $average->update($request->all());
            } else {

                $average = DefineAverage::create([
                    'academic_year_id' => $request->academic_year_id,
                    'department_id' => $request->department_id,
                    'semester_id' => $semester_id,
                    'option_id' => $option_id,
                    'grade_id' => $request->grade_id,
                    'degree_id' => $request->degree_id,
                    'value' => $request->value
                ]);
            }
            return message_success($average);

        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }
}
