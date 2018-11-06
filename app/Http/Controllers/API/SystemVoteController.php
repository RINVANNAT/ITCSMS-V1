<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;

class SystemVoteController extends Controller
{
    public function getStudentByIdCard($id_card)
    {
        $student = Student::join('studentAnnuals', 'students.id', '=', 'studentAnnuals.student_id')
            ->where('students.id_card', $id_card)
            ->orderBy('studentAnnuals.academic_year_id', 'desc')
            ->select([
                'students.id',
                'students.id_card',
                'students.dob',
                'studentAnnuals.id as student_annual_id',
                'studentAnnuals.academic_year_id',
                'studentAnnuals.department_id',
                'studentAnnuals.department_option_id',
                'studentAnnuals.degree_id',
                'studentAnnuals.grade_id',
                'studentAnnuals.group_id'
            ])
            ->first();

        return message_success($student);
    }
}
