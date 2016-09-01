<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\StudentBac2;

class SeedTestingCandidates extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * php artisan db:seed --class=SeedTestingCandidates
     */
    public function run()
    {
        $studentBac2s = StudentBac2::all()->take(2000)->toArray();
        $register_id = 1;
        foreach($studentBac2s as $studentBac2){
            $candidate = new \App\Models\Candidate();

            $candidate->name_latin = "Test User";
            $candidate->name_kh = $studentBac2['name_kh'];
            $candidate->register_id = $register_id;
            $candidate->dob = $studentBac2['dob'];

            $candidate->is_paid = false;
            $candidate->register_from = "ITC";
            $candidate->studentBac2_id = $studentBac2['id'];
            $candidate->bac_percentile = $studentBac2['percentile'];
            $candidate->active = true;
            $candidate->highschool_id = $studentBac2['highschool_id'];
            $candidate->promotion_id = 35;
            $candidate->bac_total_grade = $studentBac2['grade'];
            $candidate->bac_math_grade = $studentBac2['bac_math_grade'];
            $candidate->bac_phys_grade = $studentBac2['bac_phys_grade'];
            $candidate->bac_chem_grade = $studentBac2['bac_chem_grade'];
            $candidate->bac_year = $studentBac2['bac_year'];
            $candidate->province_id = $studentBac2['province_id'];
            $candidate->pob = $studentBac2['pob'];
            $candidate->gender_id = $studentBac2['gender_id'];
            $candidate->academic_year_id = 2015;
            $candidate->degree_id = 1;
            $candidate->exam_id = 1;
            $candidate->payslip_client_id = null;

            $candidate->created_at = Carbon::now();
            $candidate->create_uid = 1;

            $candidate->save();

            $register_id++;
        }
    }
}
