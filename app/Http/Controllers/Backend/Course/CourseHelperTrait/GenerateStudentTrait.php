<?php
/**
 * Created by PhpStorm.
 * User: vannat
 * Date: 7/19/17
 * Time: 8:07 AM
 */

namespace App\Http\Controllers\Backend\Course\CourseHelperTrait;


use App\Models\AcademicYear;
use App\Models\StudentAnnual;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait GenerateStudentTrait
{


    public function generateStudentNextAcademic(Request $request)
    {


        $studentAnnuals = StudentAnnual::where([
                ['degree_id', $request->degree_id],
                ['grade_id', $request->grade_id],
                ['academic_year_id', $request->academic_year_id],
                ['department_id', $request->department_id]
            ]);

        if($request->dept_option_id != null && $request->dept_option_id != '') {
            $studentAnnuals = $studentAnnuals->where('department_option_id', $request->dept_option_id);
        }

        $studentAnnuals = $studentAnnuals->get()->toArray();


        $currentAcademicYear = AcademicYear::find(($request->academic_year_id))->toArray();
        $nextAcademicYear = AcademicYear::find(($request->academic_year_id +1));

        $newAcademic = [];
        if(!$nextAcademicYear) {
            $explodeYearKH = explode('-', $currentAcademicYear['name_kh']);
            $explodeYearLatin = explode('-', $currentAcademicYear['name_latin']);

            $newAcademic['name_kh']     = ($explodeYearLatin[0]+1).'-'.($explodeYearLatin[1]+1);
            $newAcademic['name_latin']  = ($explodeYearLatin[0]+1).'-'.($explodeYearLatin[1]+1);
            $newAcademic['date_start']  =  date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentAcademicYear['date_start'])) . " + 1 year"));
            $newAcademic['date_end']    =  date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentAcademicYear['date_end'])) . " + 1 year"));
            $newAcademic['description'] = $currentAcademicYear['description'];
            $newAcademic['active']      = $currentAcademicYear['active'];
            $newAcademic['created_at']  = Carbon::now();
            $newAcademic['create_uid']  = auth()->id();
        }
        dd($studentAnnuals);

    }

}