<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

use App\Http\Requests;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Only for export data. @TODO NOT IMPLEMENT WITH PROJECT
 *
 * Class ExportDataController
 * @package App\Http\Controllers
 */
class ExportDataController extends Controller
{
    public function export(){
        $students = Student::join('studentAnnuals', 'students.id', '=', 'studentAnnuals.student_id')
            ->join('genders', 'genders.id', '=', 'students.gender_id')
            ->where([
                'studentAnnuals.academic_year_id' => 2017,
                'studentAnnuals.department_id' => 8,
                'studentAnnuals.grade_id' => 1
            ])
            ->select([
                'students.name_kh',
                'students.name_latin',
                'genders.name_kh as gender'
            ])
            ->orderBy('genders.name_en')
            ->orderBy('students.name_latin')
            ->get()->toArray();

        Excel::create('Student Enrollment 2017', function($excel) use ($students) {
            $excel->sheet('Student Enrollment 2017', function($sheet) use ($students) {

                $sheet->fromArray($students);

            });
        })->download('xls');
        return $students;
    }
}
