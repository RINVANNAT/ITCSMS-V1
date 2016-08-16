<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionGroupTableSeeder
 */
class PermissionGroupTableSeeder extends Seeder
{
    public function run()
    {
        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.permission_group_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement('DELETE FROM ' . config('access.permission_group_table'));
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ' . config('access.permission_group_table') . ' CASCADE');
        }

        /**
         * Create the Access groups
         */
        $group_model        = config('access.group');
        $access             = new $group_model;
        $access->name       = 'Access';
        $access->sort       = 1;
        $access->created_at = Carbon::now();
        $access->updated_at = Carbon::now();
        $access->save();

        $group_model      = config('access.group');
        $user             = new $group_model;
        $user->name       = 'User';
        $user->sort       = 1;
        $user->parent_id  = $access->id;
        $user->created_at = Carbon::now();
        $user->updated_at = Carbon::now();
        $user->save();

        $group_model      = config('access.group');
        $role             = new $group_model;
        $role->name       = 'Role';
        $role->sort       = 2;
        $role->parent_id  = $access->id;
        $role->created_at = Carbon::now();
        $role->updated_at = Carbon::now();
        $role->save();

        $group_model            = config('access.group');
        $permission             = new $group_model;
        $permission->name       = 'Permission';
        $permission->sort       = 3;
        $permission->parent_id  = $access->id;
        $permission->created_at = Carbon::now();
        $permission->updated_at = Carbon::now();
        $permission->save();

        /**
         * Create the Study & Student Affair groups
         */
        $group_model        = config('access.group');
        $student_study_affair             = new $group_model;
        $student_study_affair->name       = 'Student & Study Affair';
        $student_study_affair->sort       = 2;
        $student_study_affair->created_at = Carbon::now();
        $student_study_affair->updated_at = Carbon::now();
        $student_study_affair->save();

        $group_model      = config('access.group');
        $student             = new $group_model;
        $student->name       = 'Student';
        $student->sort       = 1;
        $student->parent_id  = $student_study_affair->id;
        $student->created_at = Carbon::now();
        $student->updated_at = Carbon::now();
        $student->save();

        $group_model      = config('access.group');
        $candidate             = new $group_model;
        $candidate->name       = 'Candidate';
        $candidate->sort       = 2;
        $candidate->parent_id  = $student_study_affair->id;
        $candidate->created_at = Carbon::now();
        $candidate->updated_at = Carbon::now();
        $candidate->save();

        /**
         * Create the Accounting groups
         */
        $group_model        = config('access.group');
        $accounting            = new $group_model;
        $accounting->name       = 'Accounting';
        $accounting->sort       = 3;
        $accounting->created_at = Carbon::now();
        $accounting->updated_at = Carbon::now();
        $accounting->save();

        $group_model      = config('access.group');
        $income             = new $group_model;
        $income->name       = 'Income';
        $income->sort       = 1;
        $income->parent_id  = $accounting->id;
        $income->created_at = Carbon::now();
        $income->updated_at = Carbon::now();
        $income->save();

        $group_model      = config('access.group');
        $outcome             = new $group_model;
        $outcome->name       = 'Outcome';
        $outcome->sort       = 2;
        $outcome->parent_id  = $accounting->id;
        $outcome->created_at = Carbon::now();
        $outcome->updated_at = Carbon::now();
        $outcome->save();


        $group_model      = config('access.group');
        $student_payment             = new $group_model;
        $student_payment->name       = 'Student Payment';
        $student_payment->sort       = 3;
        $student_payment->parent_id  = $accounting->id;
        $student_payment->created_at = Carbon::now();
        $student_payment->updated_at = Carbon::now();
        $student_payment->save();

        $group_model      = config('access.group');
        $customer            = new $group_model;
        $customer->name       = 'Customer';
        $customer->sort       = 4;
        $customer->parent_id  = $accounting->id;
        $customer->created_at = Carbon::now();
        $customer->updated_at = Carbon::now();
        $customer->save();

        /**
         * Create the Scholarship groups
         */

        $group_model      = config('access.group');
        $scholarship             = new $group_model;
        $scholarship->name       = 'Scholarship';
        $scholarship->sort       = 4;
        $scholarship->created_at = Carbon::now();
        $scholarship->updated_at = Carbon::now();
        $scholarship->save();

        /**
         * Create the Examination groups
         */

        $group_model      = config('access.group');
        $exam             = new $group_model;
        $exam->name       = 'Exam';
        $exam->sort       = 5;
        $exam->created_at = Carbon::now();
        $exam->updated_at = Carbon::now();
        $exam->save();

        /**
         * Create the Course groups
         */

        $group_model      = config('access.group');
        $course             = new $group_model;
        $course->name       = 'Course';
        $course->sort       = 6;
        $course->created_at = Carbon::now();
        $course->updated_at = Carbon::now();
        $course->save();

        /**
         * Create the Employee groups
         */

        $group_model      = config('access.group');
        $employee             = new $group_model;
        $employee->name       = 'Employee';
        $employee->sort       = 7;
        $employee->created_at = Carbon::now();
        $employee->updated_at = Carbon::now();
        $employee->save();

        /**
         * Create the Inventory groups
         */

        $group_model      = config('access.group');
        $inventory             = new $group_model;
        $inventory->name       = 'Inventory';
        $inventory->sort       = 8;
        $inventory->created_at = Carbon::now();
        $inventory->updated_at = Carbon::now();
        $inventory->save();

        /**
         * Create the Configuration groups
         */
        $group_model        = config('access.group');
        $configuration             = new $group_model;
        $configuration->name       = 'Configuration';
        $configuration->sort       = 9;
        $configuration->created_at = Carbon::now();
        $configuration->updated_at = Carbon::now();
        $configuration->save();

        $group_model      = config('access.group');
        $department             = new $group_model;
        $department->name       = 'Department';
        $department->sort       = 1;
        $department->parent_id  = $configuration->id;
        $department->created_at = Carbon::now();
        $department->updated_at = Carbon::now();
        $department->save();

        $group_model      = config('access.group');
        $degree             = new $group_model;
        $degree->name       = 'Degree';
        $degree->sort       = 2;
        $degree->parent_id  = $configuration->id;
        $degree->created_at = Carbon::now();
        $degree->updated_at = Carbon::now();
        $degree->save();

        $group_model      = config('access.group');
        $grade             = new $group_model;
        $grade->name       = 'Grade';
        $grade->sort       = 3;
        $grade->parent_id  = $configuration->id;
        $grade->created_at = Carbon::now();
        $grade->updated_at = Carbon::now();
        $grade->save();

        $group_model      = config('access.group');
        $academic_year             = new $group_model;
        $academic_year->name       = 'Academic Year';
        $academic_year->sort       = 4;
        $academic_year->parent_id  = $configuration->id;
        $academic_year->created_at = Carbon::now();
        $academic_year->updated_at = Carbon::now();
        $academic_year->save();

        $group_model      = config('access.group');
        $account             = new $group_model;
        $account->name       = 'Account';
        $account->sort       = 5;
        $account->parent_id  = $configuration->id;
        $account->created_at = Carbon::now();
        $account->updated_at = Carbon::now();
        $account->save();

        $group_model      = config('access.group');
        $building             = new $group_model;
        $building->name       = 'Building';
        $building->sort       = 6;
        $building->parent_id  = $configuration->id;
        $building->created_at = Carbon::now();
        $building->updated_at = Carbon::now();
        $building->save();

        $group_model      = config('access.group');
        $high_school             = new $group_model;
        $high_school->name       = 'High School';
        $high_school->sort       = 7;
        $high_school->parent_id  = $configuration->id;
        $high_school->created_at = Carbon::now();
        $high_school->updated_at = Carbon::now();
        $high_school->save();

        $group_model      = config('access.group');
        $income_type             = new $group_model;
        $income_type->name       = 'Income Type';
        $income_type->sort       = 8;
        $income_type->parent_id  = $configuration->id;
        $income_type->created_at = Carbon::now();
        $income_type->updated_at = Carbon::now();
        $income_type->save();

        $group_model      = config('access.group');
        $outcome_type             = new $group_model;
        $outcome_type->name       = 'Outcome Type';
        $outcome_type->sort       = 9;
        $outcome_type->parent_id  = $configuration->id;
        $outcome_type->created_at = Carbon::now();
        $outcome_type->updated_at = Carbon::now();
        $outcome_type->save();


        $group_model      = config('access.group');
        $school_fee             = new $group_model;
        $school_fee->name       = 'School & Scholarship Fee';
        $school_fee->sort       = 10;
        $school_fee->parent_id  = $configuration->id;
        $school_fee->created_at = Carbon::now();
        $school_fee->updated_at = Carbon::now();
        $school_fee->save();


        $group_model      = config('access.group');
        $room             = new $group_model;
        $room->name       = 'Room';
        $room->sort       = 11;
        $room->parent_id  = $configuration->id;
        $room->created_at = Carbon::now();
        $room->updated_at = Carbon::now();
        $room->save();

        $group_model      = config('access.group');
        $room_type             = new $group_model;
        $room_type->name       = 'Room Type';
        $room_type->sort       = 12;
        $room_type->parent_id  = $configuration->id;
        $room_type->created_at = Carbon::now();
        $room_type->updated_at = Carbon::now();
        $room_type->save();

        $group_model      = config('access.group');
        $student_bac2             = new $group_model;
        $student_bac2->name       = 'Student BacII';
        $student_bac2->sort       = 13;
        $student_bac2->parent_id  = $configuration->id;
        $student_bac2->created_at = Carbon::now();
        $student_bac2->updated_at = Carbon::now();
        $student_bac2->save();

        $group_model      = config('access.group');
        $department_option             = new $group_model;
        $department_option->name       = 'Department Option/ Technique';
        $department_option->sort       = 14;
        $department_option->parent_id  = $configuration->id;
        $department_option->created_at = Carbon::now();
        $department_option->updated_at = Carbon::now();
        $department_option->save();

        $group_model      = config('access.group');
        $promotion             = new $group_model;
        $promotion->name       = 'Promotion';
        $promotion->sort       = 15;
        $promotion->parent_id  = $configuration->id;
        $promotion->created_at = Carbon::now();
        $promotion->updated_at = Carbon::now();
        $promotion->save();

        $group_model      = config('access.group');
        $redouble             = new $group_model;
        $redouble->name       = 'Redouble';
        $redouble->sort       = 16;
        $redouble->parent_id  = $configuration->id;
        $redouble->created_at = Carbon::now();
        $redouble->updated_at = Carbon::now();
        $redouble->save();


        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}