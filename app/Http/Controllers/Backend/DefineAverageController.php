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
            'department_id' => 'required|numeric'
        ]);

        try {
            $semester_id = $request->semester_id;
            if ($request->semester_id == '') {
                $semester_id = 0;
            }

            $average = DefineAverage::where([
                'academic_year_id' => $request->academic_year_id,
                'department_id' => $request->department_id,
                'semester_id' => $semester_id
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
            'semester_id' => 'required',
            'value' => 'required|numeric'
        ]);

        try {
            $semester_id = $request->semester_id;
            if ($request->semester_id == '') {
                $semester_id = 0;
            }

            $average = DefineAverage::where([
                'academic_year_id' => $request->academic_year_id,
                'department_id' => $request->department_id,
                'semester_id' => $semester_id
            ])->first();

            if ($average instanceof DefineAverage) {
                $average->update($request->all());
            } else {
                $average = DefineAverage::create([
                    'academic_year_id' => $request->academic_year_id,
                    'department_id' => $request->department_id,
                    'semester_id' => $semester_id,
                    'value' => $request->value
                ]);
            }
            return message_success($average);

        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }
}
