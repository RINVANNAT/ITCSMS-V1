<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\StudentAnnual;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class FrontendController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {


        return redirect()
            ->route('admin.dashboard');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function macros()
    {
        return view('frontend.macros');
    }

    public function export_students()
    {
        Excel::create('List Students', function ($excel) {
            $excel->setTitle('List Students');
            $excel->setCreator('Mr. DONG Hee')->setCompany('GIC col, TD.');
            $excel->setDescription('Get all students');

            // Build the spreadsheet, passing in the payments array
            for ($i = 2012; $i <= 2017; $i++) {
                $students = DB::table('studentAnnuals as sa')
                    ->join('students as ss', 'ss.id', '=', 'sa.student_id')
                    ->where([
                        ['sa.academic_year_id', $i]
                    ])
                    ->select('ss.name_latin', 'ss.name_kh')
                    ->orderBy('ss.name_latin')
                    ->get();

                $data = array();

                foreach ($students as $student) {
                    $item = array();
                    $item['NAME_LATIN'] = $student->name_latin;
                    $item['NAME_KH'] = $student->name_kh;
                    array_push($data, $item);
                }

                $excel->sheet(($i - 1) . '-' . $i, function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            }
        })->export('xlsx');
    }
}
