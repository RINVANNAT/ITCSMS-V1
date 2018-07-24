<?php

use Illuminate\Database\Seeder;

class InternshipCompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = \App\Models\Internship\InternshipCompany::all();
        if (count($companies) > 0) {
            // $companies->delete();
        }
        $getCompaniesFromInternship = App\Models\Internship\Internship::orderBy('company', 'asc')->get();
        foreach ($getCompaniesFromInternship as $company) {
            $foundOrCreate = \App\Models\Internship\InternshipCompany::where('name', 'ilike', '%'.$company->company)->first();
            if (!($foundOrCreate instanceof \App\Models\Internship\InternshipCompany)) {
                $internshipCompany = \App\Models\Internship\InternshipCompany::create([
                    'name' => $company->company,
                    'title' => $company->title,
                    'training_field' => $company->training_field,
                    'address' => $company->address,
                    'phone' => $company->phone,
                    'hp' => $company->hot_line,
                    'mail' => $company->e_mail_address,
                    'web' => $company->web
                ]);

                $company->company_id = $internshipCompany->id;
                $company->update();
            } else {
                $company->company_id = $foundOrCreate->id;
                $company->update();
            }
        }
    }
}
