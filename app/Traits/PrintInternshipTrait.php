<?php

namespace App\Traits;


use App\Models\Degree;
use App\Models\Department;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Internship\Internship;
use App\Models\Student;
use App\Models\StudentAnnual;
use PDF;

trait PrintInternshipTrait
{
    public function print_internship($internships)
    {
        $result = array(
            'code' => 1,
            'status' => true,
            'message' => 'success'
        );

        try {
            $internships = Internship::with('internship_student_annuals')
                ->whereIn('id', json_decode($internships))
                ->get();

            foreach ($internships as $internship) {
                $department_id = null;
                $grade_id = null;
                $degree_id = null;
                $students = [];
                foreach ($internship->internship_student_annuals as $internship_student_annual) {
                    $student_annual = StudentAnnual::find($internship_student_annual->student_annual_id);
                    $department_id = $student_annual->department_id;
                    $grade_id = $student_annual->grade_id;
                    $degree_id = $student_annual->degree_id;
                    $student = Student::find($student_annual->student_id);
                    $student['gender'] = Gender::find($student->gender_id);
                    array_push($students, $student);
                }
                $internship['students'] = $students;
                $internship['department'] = Department::find($department_id);
                $internship['grade'] = Grade::find($grade_id);
                $internship['degree'] = Degree::find($degree_id);
            }
        } catch (\Exception $e) {
            $result['status'] = false;
            $result['code'] = 0;
            $result['message'] = $e->getMessage();
        }

        return PDF::loadView('backend.internship.transcript', compact('internships'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }
}