<?php

use Illuminate\Database\Seeder;

class FixMissingStudentsCauseByMissingGroup extends Seeder
{
    /**
     * This seed is used to fix bug cause by missing student record in group_student_annuals
     * No need to run this again.
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studentAnnuals = \App\Models\StudentAnnual::select([
            'studentAnnuals.id',
            'studentAnnuals.degree_id',
            'studentAnnuals.grade_id',
            'studentAnnuals.department_id',
            'studentAnnuals.department_option_id',
            \Illuminate\Support\Facades\DB::raw('CONCAT("studentAnnuals"."degree_id","studentAnnuals"."grade_id","studentAnnuals"."department_id","studentAnnuals"."department_option_id") as class')
        ])
            ->leftJoin('students','students.id','=','studentAnnuals.student_id')
            ->leftJoin('genders', 'students.gender_id', '=', 'genders.id')
            ->leftJoin('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->leftJoin('departmentOptions', 'studentAnnuals.department_option_id', '=', 'departmentOptions.id')
            ->leftJoin('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id')
            ->leftJoin('group_student_annuals', 'group_student_annuals.student_annual_id', '=', 'studentAnnuals.id')
            ->leftJoin('groups','groups.id','=','group_student_annuals.group_id')
//            ->whereNull('group_student_annuals.department_id');
            ->where('studentAnnuals.academic_year_id',2017)
            ->get()->toArray();
        $studentAnnuals = collect($studentAnnuals)->keyBy('id');

        $group_student_annuals = \App\Models\GroupStudentAnnual::select(['student_annual_id as id'])
            ->whereNull('department_id')
            ->get()->toArray();

        $group_student_annuals = collect($group_student_annuals)->keyBy('id');
        // Find missing students
        $missing_student_annual_ids_in_group = $studentAnnuals->diffKeys($group_student_annuals);
        // Now insert students records into group_student_annuals
        $missing_student_annual_ids_in_group->each(function ($item,$key){
            // Create record semester 1
           \App\Models\GroupStudentAnnual::create([
               'student_annual_id' => $key,
               'group_id' => 2,
               'semester_id' => 1,
               'created_at' => \Carbon\Carbon::now()
           ]);
           // Create record for semester 2
            \App\Models\GroupStudentAnnual::create([
                'student_annual_id' => $key,
                'group_id' => 2,
                'semester_id' => 2,
                'created_at' => \Carbon\Carbon::now()
            ]);
        });
        // Find missing class
        $missing_class = $missing_student_annual_ids_in_group->keyBy('class');
        // Find missing courses
        $missing_class->each(function ($item,$key){
           $course_annuals = \App\Models\CourseAnnual::select(['id'])
                            ->where('degree_id',$item['degree_id'])
                            ->where('grade_id',$item['grade_id'])
                            ->where('department_id',$item['department_id'])
                            ->where('department_option_id',$item['department_option_id'])
                            ->get()->toArray();
           $course_annual_ids = collect($course_annuals)->keyBy('id');
           // Create record in course annual
           $course_annual_ids->each(function($item,$key){
                \App\Models\CourseAnnualClass::create([
                    'course_annual_id' => $key,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'group_id' => 2,
                    'create_uid' => 1
                ]);
           });
        });
    }
}
