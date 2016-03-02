<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        $this->call(AccessTableSeeder::class);
        $this->call(GenderTableSeeder::class);
        $this->call(SchoolTableSeeder::class);
        $this->call(PromotionTableSeeder::class);
        $this->call(DepartmentTableSeeder::class);
        $this->call(BuildingTableSeeder::class);
        $this->call(RoomTypeTableSeeder::class);
        $this->call(AcademicYearTableSeeder::class);
        $this->call(EmployeeTableSeeder::class);
        $this->call(EmployeeGroupTableSeeder::class);
        $this->call(DegreeTableSeeder::class);
        $this->call(GradeTableSeeder::class);
        $this->call(SchoolFeeRateTableSeeder::class);
        $this->call(OriginTableSeeder::class);
        $this->call(ScholarshipTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(SemesterTableSeeder::class);
        $this->call(MarriedStatusTableSeeder::class);
        $this->call(GdeGradeTableSeeder::class);
        $this->call(FamilyStatusTableSeeder::class);
        $this->call(ScholarshipTypeTableSeeder::class);
        $this->call(ScholarshipLevelTableSeeder::class);
        $this->call(StudentStatusTableSeeder::class);
        $this->call(StudentChooseTableSeeder::class);
        $this->call(AppointerTableSeeder::class);
        $this->call(StudentWorkTypeTableSeeder::class);
        $this->call(ExamTypeTableSeeder::class);
        $this->call(DegreeDepartmentTableSeeder::class);
        $this->call(Bac2ProgramTableSeeder::class);
        $this->call(SchoolPrefixTableSeeder::class);
        $this->call(HistoryTableSeeder::class);
        $this->call(RedoubleTableSeeder::class);
        $this->call(DepartmentOptionTableSeeder::class);
        $this->call(IncomeTypeTableSeeder::class);
        $this->call(AccountTableSeeder::class);
        $this->call(StudentEvalStatusesSeeder::class);

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        Model::reguard();
    }
}
