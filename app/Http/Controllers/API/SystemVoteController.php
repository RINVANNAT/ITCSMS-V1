<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Grade;
use App\Models\Origin;
use App\Models\Student;

class SystemVoteController extends Controller
{
    public function getStudentByIdCard($id_card, $dob)
    {
        $academicYear = AcademicYear::latest()->first();
        $student = Student::join('studentAnnuals', 'students.id', '=', 'studentAnnuals.student_id')
            ->where('students.id_card', '=', $id_card)
            ->whereDate('students.dob', '=', $dob)
            ->where('studentAnnuals.academic_year_id', '=', $academicYear->id)
            ->orderBy('studentAnnuals.academic_year_id', 'desc')
            ->select([
                'students.id',
                'students.id_card',
                'students.dob',
                'students.origin_id as province_id',
                'studentAnnuals.id as student_annual_id',
                'studentAnnuals.academic_year_id',
                'studentAnnuals.department_id',
                'studentAnnuals.department_option_id',
                'studentAnnuals.degree_id',
                'studentAnnuals.grade_id',
                'studentAnnuals.group_id'
            ])
            ->first();

        return $student;
    }

    public function getQuestionOption()
    {
        $provinces = Origin::orderBy('name_kh', 'asc')
            ->get();

        $departments = Department::where('is_specialist', true)
            ->orWhere('id', 8)
            ->orderBy('name_en', 'asc')
            ->get();

        $departmentOptions = DepartmentOption::orderBy('name_en', 'asc')
            ->get();

        $degrees = Degree::orderBy('name_en', 'asc')
            ->get();

        $grades = Grade::orderBy('code', 'asc')
            ->get();

        return [
            'provinces' => $provinces,
            'departments' => $departments,
            'departmentOptions' => $departmentOptions,
            'degrees' => $degrees,
            'grades' => $grades
        ];
    }
}
