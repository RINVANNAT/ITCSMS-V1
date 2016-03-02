<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $examTypes = array(
            array(
                'name_en' => 'Entrance Engineer',
                'name_fr' => "Entrée d'Ingénieur",
                'name_kh'=> 'ប្រលងចូលថ្នាក់វិស្វករ',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_en' => 'Entrance Associate',
                'name_fr' => "Entrée d'Associé",
                'name_kh'=> 'ប្រលងចូលថ្នាក់បរិញ្ញាប័ត្ររង',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            ),
            array(
                'name_en' => 'Final Semester',
                'name_fr' => "Semestre Finale",
                'name_kh'=> 'បញ្ចប់ឆមាស',
                'create_uid'=>1,
                'created_at'=>Carbon\Carbon::now(),
                'updated_at'=>Carbon\Carbon::now()
            )
        )
        ;
        foreach($examTypes as $examType){
            DB::table('examTypes')->insert($examType);
        }
    }
}
