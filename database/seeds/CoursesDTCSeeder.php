<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesDTCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=CoursesDTCSeeder
     * @return void
     */
    public function run()
    {
        $courses = array(
            array(
                'name_kh'       => 'Géométrie',
                'name_en'       => 'Géométrie',
                'name_fr'       => 'Géométrie',
                'code'          => null,
                'time_course'   => 16,
                'time_td'       => 32,
                'time_tp'       => 0,
                'credit'        => 2,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 1,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Mécaniques1',
                'name_en'       => 'Mécaniques1',
                'name_fr'       => 'Mécaniques1',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 24,
                'time_tp'       => 0,
                'credit'        => 2.75,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 1,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Gestion&Comptabilité',
                'name_en'       => 'Gestion&Comptabilité',
                'name_fr'       => 'Gestion&Comptabilité',
                'code'          => null,
                'time_course'   => 48,
                'time_td'       => 0,
                'time_tp'       => 0,
                'credit'        => 3,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 1,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Philosophie',
                'name_en'       => 'Philosophie',
                'name_fr'       => 'Philosophie',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 0,
                'time_tp'       => 0,
                'credit'        => 2,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 1,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Environnement',
                'name_en'       => 'Environnement',
                'name_fr'       => 'Environnement',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 0,
                'time_tp'       => 0,
                'credit'        => 2,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 1,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Histoire',
                'name_en'       => 'Histoire',
                'name_fr'       => 'Histoire',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 0,
                'time_tp'       => 0,
                'credit'        => 2,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 1,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),

            // ----------- Semester 2 Year 1 -------------
            array(
                'name_kh'       => 'Mécaniques1',
                'name_en'       => 'Mécaniques1',
                'name_fr'       => 'Mécaniques1',
                'code'          => null,
                'time_course'   => 0,
                'time_td'       => 0,
                'time_tp'       => 8,
                'credit'        => 0.25,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 2,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Analyse 1',
                'name_en'       => 'Analyse 1',
                'name_fr'       => 'Analyse 1',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 32,
                'time_tp'       => 0,
                'credit'        => 3,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 2,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Themodynamique',
                'name_en'       => 'Themodynamique',
                'name_fr'       => 'Themodynamique',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 24,
                'time_tp'       => 8,
                'credit'        => 3,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 2,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Dessin Technique',
                'name_en'       => 'Dessin Technique',
                'name_fr'       => 'Dessin Technique',
                'code'          => null,
                'time_course'   => 16,
                'time_td'       => 32,
                'time_tp'       => 0,
                'credit'        => 2,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 2,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Marketing',
                'name_en'       => 'Marketing',
                'name_fr'       => 'Marketing',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 0,
                'time_tp'       => 0,
                'credit'        => 2,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 2,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Informatique',
                'name_en'       => 'Informatique',
                'name_fr'       => 'Informatique',
                'code'          => null,
                'time_course'   => 16,
                'time_td'       => 0,
                'time_tp'       => 32,
                'credit'        => 2,
                'degree_id'     => 1,
                'grade_id'      => 1,
                'department_id' => 8,
                'semester_id'   => 2,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            // ----------- Semester 1 Year 2 -------------
            array(
                'name_kh'       => 'Analyse 2',
                'name_en'       => 'Analyse 2',
                'name_fr'       => 'Analyse 2',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 32,
                'time_tp'       => 0,
                'credit'        => 3,
                'degree_id'     => 1,
                'grade_id'      => 2,
                'department_id' => 8,
                'semester_id'   => 1,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Mécaniques 2',
                'name_en'       => 'Mécaniques 2',
                'name_fr'       => 'Mécaniques 2',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 32,
                'time_tp'       => 0,
                'credit'        => 3,
                'degree_id'     => 1,
                'grade_id'      => 2,
                'department_id' => 8,
                'semester_id'   => 1,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Électricité',
                'name_en'       => 'Électricité',
                'name_fr'       => 'Électricité',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 24,
                'time_tp'       => 0,
                'credit'        => 2.75,
                'degree_id'     => 1,
                'grade_id'      => 2,
                'department_id' => 8,
                'semester_id'   => 1,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Chimie',
                'name_en'       => 'Chimie',
                'name_fr'       => 'Chimie',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 32,
                'time_tp'       => 0,
                'credit'        => 3,
                'degree_id'     => 1,
                'grade_id'      => 2,
                'department_id' => 8,
                'semester_id'   => 1,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            // ----------- Semester 2 Year 2 -------------
            array(
                'name_kh'       => 'Électricité',
                'name_en'       => 'Électricité',
                'name_fr'       => 'Électricité',
                'code'          => null,
                'time_course'   => 0,
                'time_td'       => 0,
                'time_tp'       => 8,
                'credit'        => 0.25,
                'degree_id'     => 1,
                'grade_id'      => 2,
                'department_id' => 8,
                'semester_id'   => 2,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Probabilité',
                'name_en'       => 'Probabilité',
                'name_fr'       => 'Probabilité',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 32,
                'time_tp'       => 0,
                'credit'        => 3,
                'degree_id'     => 1,
                'grade_id'      => 2,
                'department_id' => 8,
                'semester_id'   => 2,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Équations différentielles',
                'name_en'       => 'Équations différentielles',
                'name_fr'       => 'Équations différentielles',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 32,
                'time_tp'       => 0,
                'credit'        => 3,
                'degree_id'     => 1,
                'grade_id'      => 2,
                'department_id' => 8,
                'semester_id'   => 2,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            ),
            array(
                'name_kh'       => 'Vibration&Onde',
                'name_en'       => 'Vibration&Onde',
                'name_fr'       => 'Vibration&Onde',
                'code'          => null,
                'time_course'   => 32,
                'time_td'       => 24,
                'time_tp'       => 8,
                'credit'        => 3,
                'degree_id'     => 1,
                'grade_id'      => 2,
                'department_id' => 8,
                'semester_id'   => 2,
                'department_option_id' => null,
                'create_uid'    => 1,
                'created_at'    => \Carbon\Carbon::now()
            )
        );
        foreach($courses as $course){
            // Insert course program into database
            $course_id = DB::table('courses')->insertGetId($course);


            if($course['grade_id'] == 2) {
                for($i = 0;$i<26;$i++){ // 26 groups for I2
                    $this->create_course_annual($course,$i+1,$course_id);
                }
            } else {
                for($i = 0;$i<30;$i++){ // 30 groups for I1
                    $this->create_course_annual($course,$i+1,$course_id);
                }
            }
        }
    }

    private function create_course_annual($course, $group, $program_id){
        $course_annual = array(
            'name_kh' => $course['name_kh']."_group (".$group.")",
            'name_en' => $course['name_en']."_group (".$group.")",
            'name_fr' => $course['name_fr']."_group (".$group.")",
            'credit'  => $course['credit'],
            'time_course' => $course['time_course'],
            'time_td' => $course['time_td'],
            'time_tp' => $course['time_tp'],
            'semester_id' => $course['semester_id'],
            'course_id' => $program_id,
            'employee_id' => null,
            'academic_year_id' => 2017,
            'create_uid'    => 1,
            'created_at'    => \Carbon\Carbon::now()
        );

        // Insert course annual into database
        $course_annual_id = DB::table('course_annuals')->insertGetId($course_annual);

        $course_class = array(
            'group' => $group,
            'degree_id' => $course['degree_id'],
            'grade_id' => $course['grade_id'],
            'department_id' => $course['department_id'],
            'course_annual_id' => $course_annual_id,
            'department_option_id' => $course['department_option_id'],
            'create_uid'    => 1,
            'created_at'    => \Carbon\Carbon::now()
        );

        DB::table('course_annual_classes')->insert($course_class);
    }
}
