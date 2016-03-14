<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionDependencyTableSeeder
 */
class PermissionDependencyTableSeeder extends Seeder
{
    public function run()
    {
        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.permission_dependencies_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement('DELETE FROM ' . config('access.permission_dependencies_table'));
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ' . config('access.permission_dependencies_table') . ' CASCADE');
        }

        /**
         * View access management needs view backend
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-access-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-backend')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * All of the access permissions need view access management and view backend
         * Starts at id = 3 to skip view-backend, view-access-management
         */
        for ($i = 3; $i <= 21; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-backend')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);

            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-access-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * All these permissions need view backend
         */
        for ($i = 22; $i <= 128; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-backend')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * CRUD Students need view-student-management
         */
        for ($i = 23; $i <= 25; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-student-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * CRUD Candidates need view-candidate-management
         */
        for ($i = 27; $i <= 29; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-candidate-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View income management needs view accounting management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-income-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-accounting-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Incomes need view-income-management
         */
        for ($i = 32; $i <= 34; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-income-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View outcome management needs view accounting management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-outcome-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-accounting-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Outcomes need view-outcome-management
         */
        for ($i = 36; $i <= 38; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-outcome-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View student payment management needs view accounting management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-student-payment-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-accounting-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        /**
         * View customer management needs view accounting management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-customer-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-accounting-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Customers need view-customer-management
         */
        for ($i = 41; $i <= 43; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-customer-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * CRUD Scholarships need view-scholarship-management
         */
        for ($i = 45; $i <= 47; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-scholarship-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * CRUD Exams need view-exam-management
         */
        for ($i = 49; $i <= 51; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-exam-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * CRUD Courses need view-course-management
         */
        for ($i = 53; $i <= 55; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-course-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * CRUD Employees need view-employee-management
         */
        for ($i = 57; $i <= 59; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-employee-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * CRUD Inventory need view-inventory-management
         */
        for ($i = 61; $i <= 63; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-inventory-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View department management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-department-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Departments need view-department-management
         */
        for ($i = 66; $i <= 68; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-department-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View degree management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-degree-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD degrees need view-degree-management
         */
        for ($i = 70; $i <= 72; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-degree-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View grade management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-grade-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Grades need view-grade-management
         */
        for ($i = 74; $i <= 76; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-grade-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View academic year management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-academicYear-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Academic Years need view-academicYear-management
         */
        for ($i = 78; $i <= 80; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-academicYear-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View account management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-account-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Accounts need view-account-management
         */
        for ($i = 82; $i <= 84; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-account-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View building management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-building-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Buildings need view-building-management
         */
        for ($i = 86; $i <= 88; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-building-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View high school management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-highSchool-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD High Schools need view-highSchool-management
         */
        for ($i = 90; $i <= 92; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-highSchool-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View income type management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-incomeType-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Income Types need view-incomeType-management
         */
        for ($i = 94; $i <= 96; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-incomeType-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View outcome type management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-outcomeType-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Outcome Types need view-outcomeType-management
         */
        for ($i = 98; $i <= 100; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-outcomeType-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View school fees management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-schoolFee-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD School Fees need view-schoolFee-management
         */
        for ($i = 102; $i <= 104; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-schoolFee-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View room management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-room-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Rooms need view-room-management
         */
        for ($i = 106; $i <= 108; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-room-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View room type management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-roomType-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Room Types need view-roomType-management
         */
        for ($i = 110; $i <= 112; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-roomType-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View student bacII management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-studentBac2-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Student BacII need view-studentBac2-management
         */
        for ($i = 114; $i <= 116; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-studentBac2-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View department option management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-departmentOption-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Department Options need view-departmentOption-management
         */
        for ($i = 118; $i <= 120; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-departmentOption-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View promotion management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-promotion-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Promotions need view-promotion-management
         */
        for ($i = 122; $i <= 124; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-promotion-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * View redouble management needs view configuration management
         */
        DB::table(config('access.permission_dependencies_table'))->insert([
            'permission_id' => DB::table('permissions')->where('name', 'view-redouble-management')->first()->id,
            'dependency_id' => DB::table('permissions')->where('name', 'view-configuration-management')->first()->id,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        /**
         * CRUD Redoubles need view-redouble-management
         */
        for ($i = 126; $i <= 128; $i++) {
            DB::table(config('access.permission_dependencies_table'))->insert([
                'permission_id' => $i,
                'dependency_id' => DB::table('permissions')->where('name', 'view-redouble-management')->first()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        /**
         * Other dependencies here, follow above structure
         * If you have many it would be a good idea to break this up into different files and require them here
         */

        /**
         * End other dependencies
         */

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}