<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsenceNotationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=AbsenceNotationTableSeeder
     * @return void
     */
    public function run()
    {
        $notations = DB::table('averages')
            ->where(function($query) {
                $query->whereNotNull('description')
                    ->where('description', '!=', '');
            })
            ->get();

        foreach($notations as $notation) {
            $check = DB::table('absences')->where([
                ['student_annual_id', $notation->student_annual_id],
                ['course_annual_id', $notation->course_annual_id]
            ]);

            $input = [
                'student_annual_id' => $notation->student_annual_id,
                'course_annual_id' => $notation->course_annual_id,
                'notation' => $notation->description,
                'created_at' => Carbon::now()
            ];
            if(count($check->get()) > 0) {
                /*---update record absence ---*/
                $check->update($input);
            } else {
                /*---create record absence ---*/
                DB::table('absences')->insert($input);

            }
        }

    }
}
