<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Backend\CourseAnnual\CourseAnnualRepositoryContract;
use App\Repositories\Backend\CourseAnnualClass\CourseAnnualClassRepositoryContract;

class CourseAnnualModificationSeeder extends Seeder
{
    /**
     * Run the database seeds. This is used to seed data course_annual_classes from the existing course annuals. No need to run this one !
     * php artisan db:seed --class=CourseAnnualModificationSeeder
     * @return void
     */

    public function __construct(CourseAnnualRepositoryContract $courseAnnualRepo,  CourseAnnualClassRepositoryContract $courseAnnualClassRepo ) {

        $this->courseAnnuals = $courseAnnualRepo;
        $this->courseAnnualClasses = $courseAnnualClassRepo;
    }
    public function run()
    {
        $courses = DB::table('courses')
                            ->select([
                                'courses.id as course_id',
                                'name_kh', 'name_en', 'name_fr',
                                'time_course', 'time_td', 'time_tp',
                                'semester_id',
                                'degree_id',
                                'courses.grade_id', 'credit', 'create_uid',
                                'courses.department_id',
                                'courses.department_option_id',
                            ])
                            ->get();

        foreach($courses as $course){
            $array = array(
                'name'  => $course->name_kh,
                'name_kh'   => $course->name_kh,
                'name_fr'   => $course->name_fr,
                'name_en'   => $course->name_en,
                'time_course'   => $course->time_course,
                'time_td'   => $course->time_td,
                'time_tp'   => $course->time_tp,
                'semester_id'   => $course->semester_id,
                'credit'        => $course->credit,
                'degree_id' => $course->degree_id,
                'grade_id' => $course->grade_id,
                'department_id' => $course->department_id,
                'department_option_id' => $course->department_option_id,
                'course_id'     => $course->course_id,
                'academic_year_id'  => 2017,
                'created_at' => \Carbon\Carbon::now(),
                'create_uid' => 1


            );

            $courseAnnual = $this->courseAnnuals->create($array);
            if($courseAnnual) {

                $array = $array + [
                        'course_annual_id'=> $courseAnnual->id,
                        'course_session_id' => null,
                        'group'   =>  null
                    ];

                $courseAnnualClass = $this->courseAnnualClasses->create($array);
            }
//            $course_annual = DB::table('course_annuals')->insert($array);
//            if($course_annual) {
//                dd($course_annual);
////
//                $array = $array + [
//                        'course_annual_id'=> $course_annual->id,
//                        'group'   =>  null
//                    ];
//                DB::table('course_annual_classes')->insert($array);
//                DB::table('course_groups')->insert($array);
//            }


        }
    }
}
