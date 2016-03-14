<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionTableSeeder
 */
class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.permissions_table'))->truncate();
            DB::table(config('access.permission_role_table'))->truncate();
            DB::table(config('access.permission_user_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement('DELETE FROM ' . config('access.permissions_table'));
            DB::statement('DELETE FROM ' . config('access.permission_role_table'));
            DB::statement('DELETE FROM ' . config('access.permission_user_table'));
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE ' . config('access.permissions_table') . ' CASCADE');
            DB::statement('TRUNCATE TABLE ' . config('access.permission_role_table') . ' CASCADE');
            DB::statement('TRUNCATE TABLE ' . config('access.permission_user_table') . ' CASCADE');
        }

        /**
         * Don't need to assign any permissions to administrator because the all flag is set to true
         * in RoleTableSeeder.php
         */

        /**
         * Misc Access Permissions
         */
        $permission_model          = config('access.permission');
        $viewBackend               = new $permission_model;
        $viewBackend->name         = 'view-backend';
        $viewBackend->display_name = 'View Backend';
        $viewBackend->system       = true;
        $viewBackend->group_id     = 1;
        $viewBackend->sort         = 1;
        $viewBackend->created_at   = Carbon::now();
        $viewBackend->updated_at   = Carbon::now();
        $viewBackend->save();

        $permission_model                   = config('access.permission');
        $viewAccessManagement               = new $permission_model;
        $viewAccessManagement->name         = 'view-access-management';
        $viewAccessManagement->display_name = 'View Access Management';
        $viewAccessManagement->system       = true;
        $viewAccessManagement->group_id     = 1;
        $viewAccessManagement->sort         = 2;
        $viewAccessManagement->created_at   = Carbon::now();
        $viewAccessManagement->updated_at   = Carbon::now();
        $viewAccessManagement->save();

        /**
         * Access Permissions
         */

        /**
         * User
         */
        $permission_model          = config('access.permission');
        $createUsers               = new $permission_model;
        $createUsers->name         = 'create-users';
        $createUsers->display_name = 'Create Users';
        $createUsers->system       = true;
        $createUsers->group_id     = 2;
        $createUsers->sort         = 5;
        $createUsers->created_at   = Carbon::now();
        $createUsers->updated_at   = Carbon::now();
        $createUsers->save();

        $permission_model        = config('access.permission');
        $editUsers               = new $permission_model;
        $editUsers->name         = 'edit-users';
        $editUsers->display_name = 'Edit Users';
        $editUsers->system       = true;
        $editUsers->group_id     = 2;
        $editUsers->sort         = 6;
        $editUsers->created_at   = Carbon::now();
        $editUsers->updated_at   = Carbon::now();
        $editUsers->save();

        $permission_model          = config('access.permission');
        $deleteUsers               = new $permission_model;
        $deleteUsers->name         = 'delete-users';
        $deleteUsers->display_name = 'Delete Users';
        $deleteUsers->system       = true;
        $deleteUsers->group_id     = 2;
        $deleteUsers->sort         = 7;
        $deleteUsers->created_at   = Carbon::now();
        $deleteUsers->updated_at   = Carbon::now();
        $deleteUsers->save();

        $permission_model                 = config('access.permission');
        $changeUserPassword               = new $permission_model;
        $changeUserPassword->name         = 'change-user-password';
        $changeUserPassword->display_name = 'Change User Password';
        $changeUserPassword->system       = true;
        $changeUserPassword->group_id     = 2;
        $changeUserPassword->sort         = 8;
        $changeUserPassword->created_at   = Carbon::now();
        $changeUserPassword->updated_at   = Carbon::now();
        $changeUserPassword->save();

        $permission_model             = config('access.permission');
        $deactivateUser               = new $permission_model;
        $deactivateUser->name         = 'deactivate-users';
        $deactivateUser->display_name = 'Deactivate Users';
        $deactivateUser->system       = true;
        $deactivateUser->group_id     = 2;
        $deactivateUser->sort         = 9;
        $deactivateUser->created_at   = Carbon::now();
        $deactivateUser->updated_at   = Carbon::now();
        $deactivateUser->save();

        $permission_model             = config('access.permission');
        $reactivateUser               = new $permission_model;
        $reactivateUser->name         = 'reactivate-users';
        $reactivateUser->display_name = 'Re-Activate Users';
        $reactivateUser->system       = true;
        $reactivateUser->group_id     = 2;
        $reactivateUser->sort         = 11;
        $reactivateUser->created_at   = Carbon::now();
        $reactivateUser->updated_at   = Carbon::now();
        $reactivateUser->save();

        $permission_model           = config('access.permission');
        $undeleteUser               = new $permission_model;
        $undeleteUser->name         = 'undelete-users';
        $undeleteUser->display_name = 'Restore Users';
        $undeleteUser->system       = true;
        $undeleteUser->group_id     = 2;
        $undeleteUser->sort         = 13;
        $undeleteUser->created_at   = Carbon::now();
        $undeleteUser->updated_at   = Carbon::now();
        $undeleteUser->save();

        $permission_model                    = config('access.permission');
        $permanentlyDeleteUser               = new $permission_model;
        $permanentlyDeleteUser->name         = 'permanently-delete-users';
        $permanentlyDeleteUser->display_name = 'Permanently Delete Users';
        $permanentlyDeleteUser->system       = true;
        $permanentlyDeleteUser->group_id     = 2;
        $permanentlyDeleteUser->sort         = 14;
        $permanentlyDeleteUser->created_at   = Carbon::now();
        $permanentlyDeleteUser->updated_at   = Carbon::now();
        $permanentlyDeleteUser->save();

        $permission_model                      = config('access.permission');
        $resendConfirmationEmail               = new $permission_model;
        $resendConfirmationEmail->name         = 'resend-user-confirmation-email';
        $resendConfirmationEmail->display_name = 'Resend Confirmation E-mail';
        $resendConfirmationEmail->system       = true;
        $resendConfirmationEmail->group_id     = 2;
        $resendConfirmationEmail->sort         = 15;
        $resendConfirmationEmail->created_at   = Carbon::now();
        $resendConfirmationEmail->updated_at   = Carbon::now();
        $resendConfirmationEmail->save();

        /**
         * Role
         */
        $permission_model          = config('access.permission');
        $createRoles               = new $permission_model;
        $createRoles->name         = 'create-roles';
        $createRoles->display_name = 'Create Roles';
        $createRoles->system       = true;
        $createRoles->group_id     = 3;
        $createRoles->sort         = 2;
        $createRoles->created_at   = Carbon::now();
        $createRoles->updated_at   = Carbon::now();
        $createRoles->save();

        $permission_model        = config('access.permission');
        $editRoles               = new $permission_model;
        $editRoles->name         = 'edit-roles';
        $editRoles->display_name = 'Edit Roles';
        $editRoles->system       = true;
        $editRoles->group_id     = 3;
        $editRoles->sort         = 3;
        $editRoles->created_at   = Carbon::now();
        $editRoles->updated_at   = Carbon::now();
        $editRoles->save();

        $permission_model          = config('access.permission');
        $deleteRoles               = new $permission_model;
        $deleteRoles->name         = 'delete-roles';
        $deleteRoles->display_name = 'Delete Roles';
        $deleteRoles->system       = true;
        $deleteRoles->group_id     = 3;
        $deleteRoles->sort         = 4;
        $deleteRoles->created_at   = Carbon::now();
        $deleteRoles->updated_at   = Carbon::now();
        $deleteRoles->save();

        /**
         * Permission Group
         */
        $permission_model                     = config('access.permission');
        $createPermissionGroups               = new $permission_model;
        $createPermissionGroups->name         = 'create-permission-groups';
        $createPermissionGroups->display_name = 'Create Permission Groups';
        $createPermissionGroups->system       = true;
        $createPermissionGroups->group_id     = 4;
        $createPermissionGroups->sort         = 1;
        $createPermissionGroups->created_at   = Carbon::now();
        $createPermissionGroups->updated_at   = Carbon::now();
        $createPermissionGroups->save();

        $permission_model                   = config('access.permission');
        $editPermissionGroups               = new $permission_model;
        $editPermissionGroups->name         = 'edit-permission-groups';
        $editPermissionGroups->display_name = 'Edit Permission Groups';
        $editPermissionGroups->system       = true;
        $editPermissionGroups->group_id     = 4;
        $editPermissionGroups->sort         = 2;
        $editPermissionGroups->created_at   = Carbon::now();
        $editPermissionGroups->updated_at   = Carbon::now();
        $editPermissionGroups->save();

        $permission_model                     = config('access.permission');
        $deletePermissionGroups               = new $permission_model;
        $deletePermissionGroups->name         = 'delete-permission-groups';
        $deletePermissionGroups->display_name = 'Delete Permission Groups';
        $deletePermissionGroups->system       = true;
        $deletePermissionGroups->group_id     = 4;
        $deletePermissionGroups->sort         = 3;
        $deletePermissionGroups->created_at   = Carbon::now();
        $deletePermissionGroups->updated_at   = Carbon::now();
        $deletePermissionGroups->save();

        $permission_model                   = config('access.permission');
        $sortPermissionGroups               = new $permission_model;
        $sortPermissionGroups->name         = 'sort-permission-groups';
        $sortPermissionGroups->display_name = 'Sort Permission Groups';
        $sortPermissionGroups->system       = true;
        $sortPermissionGroups->group_id     = 4;
        $sortPermissionGroups->sort         = 4;
        $sortPermissionGroups->created_at   = Carbon::now();
        $sortPermissionGroups->updated_at   = Carbon::now();
        $sortPermissionGroups->save();

        /**
         * Permission
         */
        $permission_model                = config('access.permission');
        $createPermissions               = new $permission_model;
        $createPermissions->name         = 'create-permissions';
        $createPermissions->display_name = 'Create Permissions';
        $createPermissions->system       = true;
        $createPermissions->group_id     = 4;
        $createPermissions->sort         = 5;
        $createPermissions->created_at   = Carbon::now();
        $createPermissions->updated_at   = Carbon::now();
        $createPermissions->save();

        $permission_model              = config('access.permission');
        $editPermissions               = new $permission_model;
        $editPermissions->name         = 'edit-permissions';
        $editPermissions->display_name = 'Edit Permissions';
        $editPermissions->system       = true;
        $editPermissions->group_id     = 4;
        $editPermissions->sort         = 6;
        $editPermissions->created_at   = Carbon::now();
        $editPermissions->updated_at   = Carbon::now();
        $editPermissions->save();

        $permission_model                = config('access.permission');
        $deletePermissions               = new $permission_model;
        $deletePermissions->name         = 'delete-permissions';
        $deletePermissions->display_name = 'Delete Permissions';
        $deletePermissions->system       = true;
        $deletePermissions->group_id     = 4;
        $deletePermissions->sort         = 7;
        $deletePermissions->created_at   = Carbon::now();
        $deletePermissions->updated_at   = Carbon::now();
        $deletePermissions->save();

        /**
         * Student
         */

        $permission_model                   = config('access.permission');
        $viewStudentManagement               = new $permission_model;
        $viewStudentManagement->name         = 'view-student-management';
        $viewStudentManagement->display_name = 'View Student Management';
        $viewStudentManagement->system       = true;
        $viewStudentManagement->group_id     = 6;
        $viewStudentManagement->sort         = 1;
        $viewStudentManagement->created_at   = Carbon::now();
        $viewStudentManagement->updated_at   = Carbon::now();
        $viewStudentManagement->save();

        $permission_model              = config('access.permission');
        $createStudents               = new $permission_model;
        $createStudents->name         = 'create-students';
        $createStudents->display_name = 'Create Students';
        $createStudents->system       = true;
        $createStudents->group_id     = 6;
        $createStudents->sort         = 2;
        $createStudents->created_at   = Carbon::now();
        $createStudents->updated_at   = Carbon::now();
        $createStudents->save();

        $permission_model              = config('access.permission');
        $editStudents               = new $permission_model;
        $editStudents->name         = 'edit-students';
        $editStudents->display_name = 'Edit Students';
        $editStudents->system       = true;
        $editStudents->group_id     = 6;
        $editStudents->sort         = 3;
        $editStudents->created_at   = Carbon::now();
        $editStudents->updated_at   = Carbon::now();
        $editStudents->save();

        $permission_model              = config('access.permission');
        $deleteStudents               = new $permission_model;
        $deleteStudents->name         = 'delete-students';
        $deleteStudents->display_name = 'Edit Students';
        $deleteStudents->system       = true;
        $deleteStudents->group_id     = 6;
        $deleteStudents->sort         = 4;
        $deleteStudents->created_at   = Carbon::now();
        $deleteStudents->updated_at   = Carbon::now();
        $deleteStudents->save();

        /**
         * Candidate
         */

        $permission_model                   = config('access.permission');
        $viewCandidateManagement               = new $permission_model;
        $viewCandidateManagement->name         = 'view-candidate-management';
        $viewCandidateManagement->display_name = 'View Candidate Management';
        $viewCandidateManagement->system       = true;
        $viewCandidateManagement->group_id     = 7;
        $viewCandidateManagement->sort         = 1;
        $viewCandidateManagement->created_at   = Carbon::now();
        $viewCandidateManagement->updated_at   = Carbon::now();
        $viewCandidateManagement->save();

        $permission_model              = config('access.permission');
        $createCandidates               = new $permission_model;
        $createCandidates->name         = 'create-candidates';
        $createCandidates->display_name = 'Create Candidates';
        $createCandidates->system       = true;
        $createCandidates->group_id     = 7;
        $createCandidates->sort         = 2;
        $createCandidates->created_at   = Carbon::now();
        $createCandidates->updated_at   = Carbon::now();
        $createCandidates->save();

        $permission_model              = config('access.permission');
        $editCandidates               = new $permission_model;
        $editCandidates->name         = 'edit-candidates';
        $editCandidates->display_name = 'Edit Candidates';
        $editCandidates->system       = true;
        $editCandidates->group_id     = 7;
        $editCandidates->sort         = 3;
        $editCandidates->created_at   = Carbon::now();
        $editCandidates->updated_at   = Carbon::now();
        $editCandidates->save();

        $permission_model              = config('access.permission');
        $deleteCandidates               = new $permission_model;
        $deleteCandidates->name         = 'delete-candidates';
        $deleteCandidates->display_name = 'Edit Candidates';
        $deleteCandidates->system       = true;
        $deleteCandidates->group_id     = 7;
        $deleteCandidates->sort         = 4;
        $deleteCandidates->created_at   = Carbon::now();
        $deleteCandidates->updated_at   = Carbon::now();
        $deleteCandidates->save();

        /**
         * Accounting
         */
        $permission_model                   = config('access.permission');
        $viewAccountingManagement               = new $permission_model;
        $viewAccountingManagement->name         = 'view-accounting-management';
        $viewAccountingManagement->display_name = 'View Accounting Management';
        $viewAccountingManagement->system       = true;
        $viewAccountingManagement->group_id     = 8;
        $viewAccountingManagement->sort         = 1;
        $viewAccountingManagement->created_at   = Carbon::now();
        $viewAccountingManagement->updated_at   = Carbon::now();
        $viewAccountingManagement->save();

        /**
         * Income
         */

        $permission_model                   = config('access.permission');
        $viewIncomeManagement               = new $permission_model;
        $viewIncomeManagement->name         = 'view-income-management';
        $viewIncomeManagement->display_name = 'View Income Management';
        $viewIncomeManagement->system       = true;
        $viewIncomeManagement->group_id     = 9;
        $viewIncomeManagement->sort         = 1;
        $viewIncomeManagement->created_at   = Carbon::now();
        $viewIncomeManagement->updated_at   = Carbon::now();
        $viewIncomeManagement->save();

        $permission_model              = config('access.permission');
        $createIncomes               = new $permission_model;
        $createIncomes->name         = 'create-incomes';
        $createIncomes->display_name = 'Create Incomes';
        $createIncomes->system       = true;
        $createIncomes->group_id     = 9;
        $createIncomes->sort         = 2;
        $createIncomes->created_at   = Carbon::now();
        $createIncomes->updated_at   = Carbon::now();
        $createIncomes->save();

        $permission_model              = config('access.permission');
        $editIncomes               = new $permission_model;
        $editIncomes->name         = 'edit-incomes';
        $editIncomes->display_name = 'Edit Incomes';
        $editIncomes->system       = true;
        $editIncomes->group_id     = 9;
        $editIncomes->sort         = 3;
        $editIncomes->created_at   = Carbon::now();
        $editIncomes->updated_at   = Carbon::now();
        $editIncomes->save();

        $permission_model              = config('access.permission');
        $deleteIncomes               = new $permission_model;
        $deleteIncomes->name         = 'delete-incomes';
        $deleteIncomes->display_name = 'Edit Incomes';
        $deleteIncomes->system       = true;
        $deleteIncomes->group_id     = 9;
        $deleteIncomes->sort         = 4;
        $deleteIncomes->created_at   = Carbon::now();
        $deleteIncomes->updated_at   = Carbon::now();
        $deleteIncomes->save();

        /**
         * Outcome
         */

        $permission_model                   = config('access.permission');
        $viewOutcomeManagement               = new $permission_model;
        $viewOutcomeManagement->name         = 'view-outcome-management';
        $viewOutcomeManagement->display_name = 'View Outcome Management';
        $viewOutcomeManagement->system       = true;
        $viewOutcomeManagement->group_id     = 10;
        $viewOutcomeManagement->sort         = 1;
        $viewOutcomeManagement->created_at   = Carbon::now();
        $viewOutcomeManagement->updated_at   = Carbon::now();
        $viewOutcomeManagement->save();

        $permission_model              = config('access.permission');
        $createOutcomes               = new $permission_model;
        $createOutcomes->name         = 'create-outcomes';
        $createOutcomes->display_name = 'Create Outcomes';
        $createOutcomes->system       = true;
        $createOutcomes->group_id     = 10;
        $createOutcomes->sort         = 2;
        $createOutcomes->created_at   = Carbon::now();
        $createOutcomes->updated_at   = Carbon::now();
        $createOutcomes->save();

        $permission_model              = config('access.permission');
        $editOutcomes               = new $permission_model;
        $editOutcomes->name         = 'edit-outcomes';
        $editOutcomes->display_name = 'Edit Outcomes';
        $editOutcomes->system       = true;
        $editOutcomes->group_id     = 10;
        $editOutcomes->sort         = 3;
        $editOutcomes->created_at   = Carbon::now();
        $editOutcomes->updated_at   = Carbon::now();
        $editOutcomes->save();

        $permission_model              = config('access.permission');
        $deleteOutcomes               = new $permission_model;
        $deleteOutcomes->name         = 'delete-outcomes';
        $deleteOutcomes->display_name = 'Edit Outcomes';
        $deleteOutcomes->system       = true;
        $deleteOutcomes->group_id     = 10;
        $deleteOutcomes->sort         = 4;
        $deleteOutcomes->created_at   = Carbon::now();
        $deleteOutcomes->updated_at   = Carbon::now();
        $deleteOutcomes->save();

        /**
         * Student Payment
         */

        $permission_model                   = config('access.permission');
        $viewStudentPaymentManagement               = new $permission_model;
        $viewStudentPaymentManagement->name         = 'view-student-payment-management';
        $viewStudentPaymentManagement->display_name = 'View Student Payment Management';
        $viewStudentPaymentManagement->system       = true;
        $viewStudentPaymentManagement->group_id     = 11;
        $viewStudentPaymentManagement->sort         = 1;
        $viewStudentPaymentManagement->created_at   = Carbon::now();
        $viewStudentPaymentManagement->updated_at   = Carbon::now();
        $viewStudentPaymentManagement->save();

        /**
         * Customer
         */

        $permission_model                   = config('access.permission');
        $viewCustomerManagement               = new $permission_model;
        $viewCustomerManagement->name         = 'view-customer-management';
        $viewCustomerManagement->display_name = 'View Customer Management';
        $viewCustomerManagement->system       = true;
        $viewCustomerManagement->group_id     = 12;
        $viewCustomerManagement->sort         = 1;
        $viewCustomerManagement->created_at   = Carbon::now();
        $viewCustomerManagement->updated_at   = Carbon::now();
        $viewCustomerManagement->save();

        $permission_model              = config('access.permission');
        $createCustomers               = new $permission_model;
        $createCustomers->name         = 'create-customers';
        $createCustomers->display_name = 'Create Customers';
        $createCustomers->system       = true;
        $createCustomers->group_id     = 12;
        $createCustomers->sort         = 2;
        $createCustomers->created_at   = Carbon::now();
        $createCustomers->updated_at   = Carbon::now();
        $createCustomers->save();

        $permission_model              = config('access.permission');
        $editCustomers               = new $permission_model;
        $editCustomers->name         = 'edit-customers';
        $editCustomers->display_name = 'Edit Customers';
        $editCustomers->system       = true;
        $editCustomers->group_id     = 12;
        $editCustomers->sort         = 3;
        $editCustomers->created_at   = Carbon::now();
        $editCustomers->updated_at   = Carbon::now();
        $editCustomers->save();

        $permission_model              = config('access.permission');
        $deleteCustomers               = new $permission_model;
        $deleteCustomers->name         = 'delete-customers';
        $deleteCustomers->display_name = 'Edit Customers';
        $deleteCustomers->system       = true;
        $deleteCustomers->group_id     = 12;
        $deleteCustomers->sort         = 4;
        $deleteCustomers->created_at   = Carbon::now();
        $deleteCustomers->updated_at   = Carbon::now();
        $deleteCustomers->save();

        /**
         * Scholarship
         */

        $permission_model                   = config('access.permission');
        $viewScholarshipManagement               = new $permission_model;
        $viewScholarshipManagement->name         = 'view-scholarship-management';
        $viewScholarshipManagement->display_name = 'View Scholarship Management';
        $viewScholarshipManagement->system       = true;
        $viewScholarshipManagement->group_id     = 13;
        $viewScholarshipManagement->sort         = 1;
        $viewScholarshipManagement->created_at   = Carbon::now();
        $viewScholarshipManagement->updated_at   = Carbon::now();
        $viewScholarshipManagement->save();

        $permission_model              = config('access.permission');
        $createScholarships               = new $permission_model;
        $createScholarships->name         = 'create-scholarships';
        $createScholarships->display_name = 'Create Scholarships';
        $createScholarships->system       = true;
        $createScholarships->group_id     = 13;
        $createScholarships->sort         = 2;
        $createScholarships->created_at   = Carbon::now();
        $createScholarships->updated_at   = Carbon::now();
        $createScholarships->save();

        $permission_model              = config('access.permission');
        $editScholarships               = new $permission_model;
        $editScholarships->name         = 'edit-scholarships';
        $editScholarships->display_name = 'Edit Scholarships';
        $editScholarships->system       = true;
        $editScholarships->group_id     = 13;
        $editScholarships->sort         = 3;
        $editScholarships->created_at   = Carbon::now();
        $editScholarships->updated_at   = Carbon::now();
        $editScholarships->save();

        $permission_model              = config('access.permission');
        $deleteScholarships               = new $permission_model;
        $deleteScholarships->name         = 'delete-scholarships';
        $deleteScholarships->display_name = 'Edit Scholarships';
        $deleteScholarships->system       = true;
        $deleteScholarships->group_id     = 13;
        $deleteScholarships->sort         = 4;
        $deleteScholarships->created_at   = Carbon::now();
        $deleteScholarships->updated_at   = Carbon::now();
        $deleteScholarships->save();

        /**
         * Exam
         */

        $permission_model                   = config('access.permission');
        $viewExamManagement               = new $permission_model;
        $viewExamManagement->name         = 'view-exam-management';
        $viewExamManagement->display_name = 'View Exam Management';
        $viewExamManagement->system       = true;
        $viewExamManagement->group_id     = 14;
        $viewExamManagement->sort         = 1;
        $viewExamManagement->created_at   = Carbon::now();
        $viewExamManagement->updated_at   = Carbon::now();
        $viewExamManagement->save();

        $permission_model              = config('access.permission');
        $createExams               = new $permission_model;
        $createExams->name         = 'create-exams';
        $createExams->display_name = 'Create Exams';
        $createExams->system       = true;
        $createExams->group_id     = 14;
        $createExams->sort         = 2;
        $createExams->created_at   = Carbon::now();
        $createExams->updated_at   = Carbon::now();
        $createExams->save();

        $permission_model              = config('access.permission');
        $editExams               = new $permission_model;
        $editExams->name         = 'edit-exams';
        $editExams->display_name = 'Edit Exams';
        $editExams->system       = true;
        $editExams->group_id     = 14;
        $editExams->sort         = 3;
        $editExams->created_at   = Carbon::now();
        $editExams->updated_at   = Carbon::now();
        $editExams->save();

        $permission_model              = config('access.permission');
        $deleteExams               = new $permission_model;
        $deleteExams->name         = 'delete-exams';
        $deleteExams->display_name = 'Edit Exams';
        $deleteExams->system       = true;
        $deleteExams->group_id     = 14;
        $deleteExams->sort         = 4;
        $deleteExams->created_at   = Carbon::now();
        $deleteExams->updated_at   = Carbon::now();
        $deleteExams->save();


        /**
         * Course
         */

        $permission_model                   = config('access.permission');
        $viewCourseManagement               = new $permission_model;
        $viewCourseManagement->name         = 'view-course-management';
        $viewCourseManagement->display_name = 'View Course Management';
        $viewCourseManagement->system       = true;
        $viewCourseManagement->group_id     = 15;
        $viewCourseManagement->sort         = 1;
        $viewCourseManagement->created_at   = Carbon::now();
        $viewCourseManagement->updated_at   = Carbon::now();
        $viewCourseManagement->save();

        $permission_model              = config('access.permission');
        $createCourses               = new $permission_model;
        $createCourses->name         = 'create-courses';
        $createCourses->display_name = 'Create Courses';
        $createCourses->system       = true;
        $createCourses->group_id     = 15;
        $createCourses->sort         = 2;
        $createCourses->created_at   = Carbon::now();
        $createCourses->updated_at   = Carbon::now();
        $createCourses->save();

        $permission_model              = config('access.permission');
        $editCourses               = new $permission_model;
        $editCourses->name         = 'edit-courses';
        $editCourses->display_name = 'Edit Courses';
        $editCourses->system       = true;
        $editCourses->group_id     = 15;
        $editCourses->sort         = 3;
        $editCourses->created_at   = Carbon::now();
        $editCourses->updated_at   = Carbon::now();
        $editCourses->save();

        $permission_model              = config('access.permission');
        $deleteCourses               = new $permission_model;
        $deleteCourses->name         = 'delete-courses';
        $deleteCourses->display_name = 'Edit Courses';
        $deleteCourses->system       = true;
        $deleteCourses->group_id     = 15;
        $deleteCourses->sort         = 4;
        $deleteCourses->created_at   = Carbon::now();
        $deleteCourses->updated_at   = Carbon::now();
        $deleteCourses->save();

        /**
         * Employee
         */

        $permission_model                   = config('access.permission');
        $viewEmployeeManagement               = new $permission_model;
        $viewEmployeeManagement->name         = 'view-employee-management';
        $viewEmployeeManagement->display_name = 'View Employee Management';
        $viewEmployeeManagement->system       = true;
        $viewEmployeeManagement->group_id     = 16;
        $viewEmployeeManagement->sort         = 1;
        $viewEmployeeManagement->created_at   = Carbon::now();
        $viewEmployeeManagement->updated_at   = Carbon::now();
        $viewEmployeeManagement->save();

        $permission_model              = config('access.permission');
        $createEmployees               = new $permission_model;
        $createEmployees->name         = 'create-employees';
        $createEmployees->display_name = 'Create Employees';
        $createEmployees->system       = true;
        $createEmployees->group_id     = 16;
        $createEmployees->sort         = 2;
        $createEmployees->created_at   = Carbon::now();
        $createEmployees->updated_at   = Carbon::now();
        $createEmployees->save();

        $permission_model              = config('access.permission');
        $editEmployees               = new $permission_model;
        $editEmployees->name         = 'edit-employees';
        $editEmployees->display_name = 'Edit Employees';
        $editEmployees->system       = true;
        $editEmployees->group_id     = 16;
        $editEmployees->sort         = 3;
        $editEmployees->created_at   = Carbon::now();
        $editEmployees->updated_at   = Carbon::now();
        $editEmployees->save();

        $permission_model              = config('access.permission');
        $deleteEmployees               = new $permission_model;
        $deleteEmployees->name         = 'delete-employees';
        $deleteEmployees->display_name = 'Edit Employees';
        $deleteEmployees->system       = true;
        $deleteEmployees->group_id     = 16;
        $deleteEmployees->sort         = 4;
        $deleteEmployees->created_at   = Carbon::now();
        $deleteEmployees->updated_at   = Carbon::now();
        $deleteEmployees->save();

        /**
         * Inventory
         */

        $permission_model                   = config('access.permission');
        $viewInventoryManagement               = new $permission_model;
        $viewInventoryManagement->name         = 'view-inventory-management';
        $viewInventoryManagement->display_name = 'View Inventory Management';
        $viewInventoryManagement->system       = true;
        $viewInventoryManagement->group_id     = 17;
        $viewInventoryManagement->sort         = 1;
        $viewInventoryManagement->created_at   = Carbon::now();
        $viewInventoryManagement->updated_at   = Carbon::now();
        $viewInventoryManagement->save();

        $permission_model              = config('access.permission');
        $createInventorys               = new $permission_model;
        $createInventorys->name         = 'create-inventorys';
        $createInventorys->display_name = 'Create Inventorys';
        $createInventorys->system       = true;
        $createInventorys->group_id     = 17;
        $createInventorys->sort         = 2;
        $createInventorys->created_at   = Carbon::now();
        $createInventorys->updated_at   = Carbon::now();
        $createInventorys->save();

        $permission_model              = config('access.permission');
        $editInventorys               = new $permission_model;
        $editInventorys->name         = 'edit-inventorys';
        $editInventorys->display_name = 'Edit Inventorys';
        $editInventorys->system       = true;
        $editInventorys->group_id     = 17;
        $editInventorys->sort         = 3;
        $editInventorys->created_at   = Carbon::now();
        $editInventorys->updated_at   = Carbon::now();
        $editInventorys->save();

        $permission_model              = config('access.permission');
        $deleteInventorys               = new $permission_model;
        $deleteInventorys->name         = 'delete-inventorys';
        $deleteInventorys->display_name = 'Edit Inventorys';
        $deleteInventorys->system       = true;
        $deleteInventorys->group_id     = 17;
        $deleteInventorys->sort         = 4;
        $deleteInventorys->created_at   = Carbon::now();
        $deleteInventorys->updated_at   = Carbon::now();
        $deleteInventorys->save();


        /**
         * Configuration
         */

        $permission_model                   = config('access.permission');
        $viewConfigurationManagement               = new $permission_model;
        $viewConfigurationManagement->name         = 'view-configuration-management';
        $viewConfigurationManagement->display_name = 'View Configuration Management';
        $viewConfigurationManagement->system       = true;
        $viewConfigurationManagement->group_id     = 18;
        $viewConfigurationManagement->sort         = 1;
        $viewConfigurationManagement->created_at   = Carbon::now();
        $viewConfigurationManagement->updated_at   = Carbon::now();
        $viewConfigurationManagement->save();

        /**
         * Department
         */

        $permission_model                   = config('access.permission');
        $viewDepartmentManagement               = new $permission_model;
        $viewDepartmentManagement->name         = 'view-department-management';
        $viewDepartmentManagement->display_name = 'View Department Management';
        $viewDepartmentManagement->system       = true;
        $viewDepartmentManagement->group_id     = 19;
        $viewDepartmentManagement->sort         = 1;
        $viewDepartmentManagement->created_at   = Carbon::now();
        $viewDepartmentManagement->updated_at   = Carbon::now();
        $viewDepartmentManagement->save();

        $permission_model              = config('access.permission');
        $createDepartments               = new $permission_model;
        $createDepartments->name         = 'create-departments';
        $createDepartments->display_name = 'Create Departments';
        $createDepartments->system       = true;
        $createDepartments->group_id     = 19;
        $createDepartments->sort         = 2;
        $createDepartments->created_at   = Carbon::now();
        $createDepartments->updated_at   = Carbon::now();
        $createDepartments->save();

        $permission_model              = config('access.permission');
        $editDepartments               = new $permission_model;
        $editDepartments->name         = 'edit-departments';
        $editDepartments->display_name = 'Edit Departments';
        $editDepartments->system       = true;
        $editDepartments->group_id     = 19;
        $editDepartments->sort         = 3;
        $editDepartments->created_at   = Carbon::now();
        $editDepartments->updated_at   = Carbon::now();
        $editDepartments->save();

        $permission_model              = config('access.permission');
        $deleteDepartments               = new $permission_model;
        $deleteDepartments->name         = 'delete-departments';
        $deleteDepartments->display_name = 'Delete Departments';
        $deleteDepartments->system       = true;
        $deleteDepartments->group_id     = 19;
        $deleteDepartments->sort         = 4;
        $deleteDepartments->created_at   = Carbon::now();
        $deleteDepartments->updated_at   = Carbon::now();
        $deleteDepartments->save();

        /**
         * Degree
         */

        $permission_model                   = config('access.permission');
        $viewDegreeManagement               = new $permission_model;
        $viewDegreeManagement->name         = 'view-degree-management';
        $viewDegreeManagement->display_name = 'View Degree Management';
        $viewDegreeManagement->system       = true;
        $viewDegreeManagement->group_id     = 20;
        $viewDegreeManagement->sort         = 1;
        $viewDegreeManagement->created_at   = Carbon::now();
        $viewDegreeManagement->updated_at   = Carbon::now();
        $viewDegreeManagement->save();

        $permission_model              = config('access.permission');
        $createDegrees               = new $permission_model;
        $createDegrees->name         = 'create-degrees';
        $createDegrees->display_name = 'Create Degrees';
        $createDegrees->system       = true;
        $createDegrees->group_id     = 20;
        $createDegrees->sort         = 2;
        $createDegrees->created_at   = Carbon::now();
        $createDegrees->updated_at   = Carbon::now();
        $createDegrees->save();

        $permission_model              = config('access.permission');
        $editDegrees               = new $permission_model;
        $editDegrees->name         = 'edit-degrees';
        $editDegrees->display_name = 'Edit Degrees';
        $editDegrees->system       = true;
        $editDegrees->group_id     = 20;
        $editDegrees->sort         = 3;
        $editDegrees->created_at   = Carbon::now();
        $editDegrees->updated_at   = Carbon::now();
        $editDegrees->save();

        $permission_model              = config('access.permission');
        $deleteDegrees               = new $permission_model;
        $deleteDegrees->name         = 'delete-degrees';
        $deleteDegrees->display_name = 'Delete Degrees';
        $deleteDegrees->system       = true;
        $deleteDegrees->group_id     = 20;
        $deleteDegrees->sort         = 4;
        $deleteDegrees->created_at   = Carbon::now();
        $deleteDegrees->updated_at   = Carbon::now();
        $deleteDegrees->save();

        /**
         * Grade
         */

        $permission_model                   = config('access.permission');
        $viewGradeManagement               = new $permission_model;
        $viewGradeManagement->name         = 'view-grade-management';
        $viewGradeManagement->display_name = 'View Grade Management';
        $viewGradeManagement->system       = true;
        $viewGradeManagement->group_id     = 21;
        $viewGradeManagement->sort         = 1;
        $viewGradeManagement->created_at   = Carbon::now();
        $viewGradeManagement->updated_at   = Carbon::now();
        $viewGradeManagement->save();

        $permission_model              = config('access.permission');
        $createGrades               = new $permission_model;
        $createGrades->name         = 'create-grades';
        $createGrades->display_name = 'Create Grades';
        $createGrades->system       = true;
        $createGrades->group_id     = 21;
        $createGrades->sort         = 2;
        $createGrades->created_at   = Carbon::now();
        $createGrades->updated_at   = Carbon::now();
        $createGrades->save();

        $permission_model              = config('access.permission');
        $editGrades               = new $permission_model;
        $editGrades->name         = 'edit-grades';
        $editGrades->display_name = 'Edit Grades';
        $editGrades->system       = true;
        $editGrades->group_id     = 21;
        $editGrades->sort         = 3;
        $editGrades->created_at   = Carbon::now();
        $editGrades->updated_at   = Carbon::now();
        $editGrades->save();

        $permission_model              = config('access.permission');
        $deleteGrades               = new $permission_model;
        $deleteGrades->name         = 'delete-grades';
        $deleteGrades->display_name = 'Delete Grades';
        $deleteGrades->system       = true;
        $deleteGrades->group_id     = 21;
        $deleteGrades->sort         = 4;
        $deleteGrades->created_at   = Carbon::now();
        $deleteGrades->updated_at   = Carbon::now();
        $deleteGrades->save();

        /**
         * Academic Year
         */

        $permission_model                   = config('access.permission');
        $viewAcademicYearManagement               = new $permission_model;
        $viewAcademicYearManagement->name         = 'view-academicYear-management';
        $viewAcademicYearManagement->display_name = 'View Academic Year Management';
        $viewAcademicYearManagement->system       = true;
        $viewAcademicYearManagement->group_id     = 22;
        $viewAcademicYearManagement->sort         = 1;
        $viewAcademicYearManagement->created_at   = Carbon::now();
        $viewAcademicYearManagement->updated_at   = Carbon::now();
        $viewAcademicYearManagement->save();

        $permission_model              = config('access.permission');
        $createAcademicYears               = new $permission_model;
        $createAcademicYears->name         = 'create-academicYears';
        $createAcademicYears->display_name = 'Create Academic Years';
        $createAcademicYears->system       = true;
        $createAcademicYears->group_id     = 22;
        $createAcademicYears->sort         = 2;
        $createAcademicYears->created_at   = Carbon::now();
        $createAcademicYears->updated_at   = Carbon::now();
        $createAcademicYears->save();

        $permission_model              = config('access.permission');
        $editAcademicYears               = new $permission_model;
        $editAcademicYears->name         = 'edit-academicYears';
        $editAcademicYears->display_name = 'Edit Academic Years';
        $editAcademicYears->system       = true;
        $editAcademicYears->group_id     = 22;
        $editAcademicYears->sort         = 3;
        $editAcademicYears->created_at   = Carbon::now();
        $editAcademicYears->updated_at   = Carbon::now();
        $editAcademicYears->save();

        $permission_model              = config('access.permission');
        $deleteAcademicYears               = new $permission_model;
        $deleteAcademicYears->name         = 'delete-academicYears';
        $deleteAcademicYears->display_name = 'Delete Academic Years';
        $deleteAcademicYears->system       = true;
        $deleteAcademicYears->group_id     = 22;
        $deleteAcademicYears->sort         = 4;
        $deleteAcademicYears->created_at   = Carbon::now();
        $deleteAcademicYears->updated_at   = Carbon::now();
        $deleteAcademicYears->save();

        /**
         * Account
         */

        $permission_model                   = config('access.permission');
        $viewAccountManagement               = new $permission_model;
        $viewAccountManagement->name         = 'view-account-management';
        $viewAccountManagement->display_name = 'View Account Management';
        $viewAccountManagement->system       = true;
        $viewAccountManagement->group_id     = 23;
        $viewAccountManagement->sort         = 1;
        $viewAccountManagement->created_at   = Carbon::now();
        $viewAccountManagement->updated_at   = Carbon::now();
        $viewAccountManagement->save();

        $permission_model              = config('access.permission');
        $createAccounts               = new $permission_model;
        $createAccounts->name         = 'create-accounts';
        $createAccounts->display_name = 'Create Accounts';
        $createAccounts->system       = true;
        $createAccounts->group_id     = 23;
        $createAccounts->sort         = 2;
        $createAccounts->created_at   = Carbon::now();
        $createAccounts->updated_at   = Carbon::now();
        $createAccounts->save();

        $permission_model              = config('access.permission');
        $editAccounts               = new $permission_model;
        $editAccounts->name         = 'edit-accounts';
        $editAccounts->display_name = 'Edit Accounts';
        $editAccounts->system       = true;
        $editAccounts->group_id     = 23;
        $editAccounts->sort         = 3;
        $editAccounts->created_at   = Carbon::now();
        $editAccounts->updated_at   = Carbon::now();
        $editAccounts->save();

        $permission_model              = config('access.permission');
        $deleteAccounts               = new $permission_model;
        $deleteAccounts->name         = 'delete-accounts';
        $deleteAccounts->display_name = 'Delete Accounts';
        $deleteAccounts->system       = true;
        $deleteAccounts->group_id     = 23;
        $deleteAccounts->sort         = 4;
        $deleteAccounts->created_at   = Carbon::now();
        $deleteAccounts->updated_at   = Carbon::now();
        $deleteAccounts->save();

        /**
         * Building
         */

        $permission_model                   = config('access.permission');
        $viewBuildingManagement               = new $permission_model;
        $viewBuildingManagement->name         = 'view-building-management';
        $viewBuildingManagement->display_name = 'View Building Management';
        $viewBuildingManagement->system       = true;
        $viewBuildingManagement->group_id     = 24;
        $viewBuildingManagement->sort         = 1;
        $viewBuildingManagement->created_at   = Carbon::now();
        $viewBuildingManagement->updated_at   = Carbon::now();
        $viewBuildingManagement->save();

        $permission_model              = config('access.permission');
        $createBuildings               = new $permission_model;
        $createBuildings->name         = 'create-buildings';
        $createBuildings->display_name = 'Create Buildings';
        $createBuildings->system       = true;
        $createBuildings->group_id     = 24;
        $createBuildings->sort         = 2;
        $createBuildings->created_at   = Carbon::now();
        $createBuildings->updated_at   = Carbon::now();
        $createBuildings->save();

        $permission_model              = config('access.permission');
        $editBuildings               = new $permission_model;
        $editBuildings->name         = 'edit-buildings';
        $editBuildings->display_name = 'Edit Buildings';
        $editBuildings->system       = true;
        $editBuildings->group_id     = 24;
        $editBuildings->sort         = 3;
        $editBuildings->created_at   = Carbon::now();
        $editBuildings->updated_at   = Carbon::now();
        $editBuildings->save();

        $permission_model              = config('access.permission');
        $deleteBuildings               = new $permission_model;
        $deleteBuildings->name         = 'delete-buildings';
        $deleteBuildings->display_name = 'Delete Buildings';
        $deleteBuildings->system       = true;
        $deleteBuildings->group_id     = 24;
        $deleteBuildings->sort         = 4;
        $deleteBuildings->created_at   = Carbon::now();
        $deleteBuildings->updated_at   = Carbon::now();
        $deleteBuildings->save();

        /**
         * High School
         */

        $permission_model                   = config('access.permission');
        $viewHighSchoolManagement               = new $permission_model;
        $viewHighSchoolManagement->name         = 'view-highSchool-management';
        $viewHighSchoolManagement->display_name = 'View High School Management';
        $viewHighSchoolManagement->system       = true;
        $viewHighSchoolManagement->group_id     = 25;
        $viewHighSchoolManagement->sort         = 1;
        $viewHighSchoolManagement->created_at   = Carbon::now();
        $viewHighSchoolManagement->updated_at   = Carbon::now();
        $viewHighSchoolManagement->save();

        $permission_model              = config('access.permission');
        $createHighSchools               = new $permission_model;
        $createHighSchools->name         = 'create-highSchools';
        $createHighSchools->display_name = 'Create High Schools';
        $createHighSchools->system       = true;
        $createHighSchools->group_id     = 25;
        $createHighSchools->sort         = 2;
        $createHighSchools->created_at   = Carbon::now();
        $createHighSchools->updated_at   = Carbon::now();
        $createHighSchools->save();

        $permission_model              = config('access.permission');
        $editHighSchools               = new $permission_model;
        $editHighSchools->name         = 'edit-highSchools';
        $editHighSchools->display_name = 'Edit High Schools';
        $editHighSchools->system       = true;
        $editHighSchools->group_id     = 25;
        $editHighSchools->sort         = 3;
        $editHighSchools->created_at   = Carbon::now();
        $editHighSchools->updated_at   = Carbon::now();
        $editHighSchools->save();

        $permission_model              = config('access.permission');
        $deleteHighSchools               = new $permission_model;
        $deleteHighSchools->name         = 'delete-highSchools';
        $deleteHighSchools->display_name = 'Delete High Schools';
        $deleteHighSchools->system       = true;
        $deleteHighSchools->group_id     = 25;
        $deleteHighSchools->sort         = 4;
        $deleteHighSchools->created_at   = Carbon::now();
        $deleteHighSchools->updated_at   = Carbon::now();
        $deleteHighSchools->save();

        /**
         * IncomeType
         */

        $permission_model                   = config('access.permission');
        $viewIncomeTypeManagement               = new $permission_model;
        $viewIncomeTypeManagement->name         = 'view-incomeType-management';
        $viewIncomeTypeManagement->display_name = 'View Income Type Management';
        $viewIncomeTypeManagement->system       = true;
        $viewIncomeTypeManagement->group_id     = 26;
        $viewIncomeTypeManagement->sort         = 1;
        $viewIncomeTypeManagement->created_at   = Carbon::now();
        $viewIncomeTypeManagement->updated_at   = Carbon::now();
        $viewIncomeTypeManagement->save();

        $permission_model              = config('access.permission');
        $createIncomeTypes               = new $permission_model;
        $createIncomeTypes->name         = 'create-incomeTypes';
        $createIncomeTypes->display_name = 'Create Income Types';
        $createIncomeTypes->system       = true;
        $createIncomeTypes->group_id     = 26;
        $createIncomeTypes->sort         = 2;
        $createIncomeTypes->created_at   = Carbon::now();
        $createIncomeTypes->updated_at   = Carbon::now();
        $createIncomeTypes->save();

        $permission_model              = config('access.permission');
        $editIncomeTypes               = new $permission_model;
        $editIncomeTypes->name         = 'edit-incomeTypes';
        $editIncomeTypes->display_name = 'Edit Income Types';
        $editIncomeTypes->system       = true;
        $editIncomeTypes->group_id     = 26;
        $editIncomeTypes->sort         = 3;
        $editIncomeTypes->created_at   = Carbon::now();
        $editIncomeTypes->updated_at   = Carbon::now();
        $editIncomeTypes->save();

        $permission_model              = config('access.permission');
        $deleteIncomeTypes               = new $permission_model;
        $deleteIncomeTypes->name         = 'delete-incomeTypes';
        $deleteIncomeTypes->display_name = 'Delete Income Types';
        $deleteIncomeTypes->system       = true;
        $deleteIncomeTypes->group_id     = 26;
        $deleteIncomeTypes->sort         = 4;
        $deleteIncomeTypes->created_at   = Carbon::now();
        $deleteIncomeTypes->updated_at   = Carbon::now();
        $deleteIncomeTypes->save();

        /**
         * Outcome Type
         */

        $permission_model                   = config('access.permission');
        $viewOutcomeTypeManagement               = new $permission_model;
        $viewOutcomeTypeManagement->name         = 'view-outcomeType-management';
        $viewOutcomeTypeManagement->display_name = 'View Outcome Type Management';
        $viewOutcomeTypeManagement->system       = true;
        $viewOutcomeTypeManagement->group_id     = 27;
        $viewOutcomeTypeManagement->sort         = 1;
        $viewOutcomeTypeManagement->created_at   = Carbon::now();
        $viewOutcomeTypeManagement->updated_at   = Carbon::now();
        $viewOutcomeTypeManagement->save();

        $permission_model              = config('access.permission');
        $createOutcomeTypes               = new $permission_model;
        $createOutcomeTypes->name         = 'create-outcomeTypes';
        $createOutcomeTypes->display_name = 'Create Outcome Types';
        $createOutcomeTypes->system       = true;
        $createOutcomeTypes->group_id     = 27;
        $createOutcomeTypes->sort         = 2;
        $createOutcomeTypes->created_at   = Carbon::now();
        $createOutcomeTypes->updated_at   = Carbon::now();
        $createOutcomeTypes->save();

        $permission_model              = config('access.permission');
        $editOutcomeTypes               = new $permission_model;
        $editOutcomeTypes->name         = 'edit-outcomeTypes';
        $editOutcomeTypes->display_name = 'Edit Outcome Types';
        $editOutcomeTypes->system       = true;
        $editOutcomeTypes->group_id     = 27;
        $editOutcomeTypes->sort         = 3;
        $editOutcomeTypes->created_at   = Carbon::now();
        $editOutcomeTypes->updated_at   = Carbon::now();
        $editOutcomeTypes->save();

        $permission_model              = config('access.permission');
        $deleteOutcomeTypes               = new $permission_model;
        $deleteOutcomeTypes->name         = 'delete-outcomeTypes';
        $deleteOutcomeTypes->display_name = 'Delete Outcome Types';
        $deleteOutcomeTypes->system       = true;
        $deleteOutcomeTypes->group_id     = 27;
        $deleteOutcomeTypes->sort         = 4;
        $deleteOutcomeTypes->created_at   = Carbon::now();
        $deleteOutcomeTypes->updated_at   = Carbon::now();
        $deleteOutcomeTypes->save();

        /**
         * School & Scholarship Fee
         */

        $permission_model                   = config('access.permission');
        $viewSchoolFeeManagement               = new $permission_model;
        $viewSchoolFeeManagement->name         = 'view-schoolFee-management';
        $viewSchoolFeeManagement->display_name = 'View School Fee Management';
        $viewSchoolFeeManagement->system       = true;
        $viewSchoolFeeManagement->group_id     = 28;
        $viewSchoolFeeManagement->sort         = 1;
        $viewSchoolFeeManagement->created_at   = Carbon::now();
        $viewSchoolFeeManagement->updated_at   = Carbon::now();
        $viewSchoolFeeManagement->save();

        $permission_model              = config('access.permission');
        $createSchoolFees               = new $permission_model;
        $createSchoolFees->name         = 'create-schoolFees';
        $createSchoolFees->display_name = 'Create School Fees';
        $createSchoolFees->system       = true;
        $createSchoolFees->group_id     = 28;
        $createSchoolFees->sort         = 2;
        $createSchoolFees->created_at   = Carbon::now();
        $createSchoolFees->updated_at   = Carbon::now();
        $createSchoolFees->save();

        $permission_model              = config('access.permission');
        $editSchoolFees               = new $permission_model;
        $editSchoolFees->name         = 'edit-schoolFees';
        $editSchoolFees->display_name = 'Edit School Fees';
        $editSchoolFees->system       = true;
        $editSchoolFees->group_id     = 28;
        $editSchoolFees->sort         = 3;
        $editSchoolFees->created_at   = Carbon::now();
        $editSchoolFees->updated_at   = Carbon::now();
        $editSchoolFees->save();

        $permission_model              = config('access.permission');
        $deleteSchoolFees               = new $permission_model;
        $deleteSchoolFees->name         = 'delete-schoolFees';
        $deleteSchoolFees->display_name = 'Delete School Fees';
        $deleteSchoolFees->system       = true;
        $deleteSchoolFees->group_id     = 28;
        $deleteSchoolFees->sort         = 4;
        $deleteSchoolFees->created_at   = Carbon::now();
        $deleteSchoolFees->updated_at   = Carbon::now();
        $deleteSchoolFees->save();

        /**
         * Room
         */

        $permission_model                   = config('access.permission');
        $viewRoomManagement               = new $permission_model;
        $viewRoomManagement->name         = 'view-room-management';
        $viewRoomManagement->display_name = 'View Room Management';
        $viewRoomManagement->system       = true;
        $viewRoomManagement->group_id     = 29;
        $viewRoomManagement->sort         = 1;
        $viewRoomManagement->created_at   = Carbon::now();
        $viewRoomManagement->updated_at   = Carbon::now();
        $viewRoomManagement->save();

        $permission_model              = config('access.permission');
        $createRooms               = new $permission_model;
        $createRooms->name         = 'create-rooms';
        $createRooms->display_name = 'Create Rooms';
        $createRooms->system       = true;
        $createRooms->group_id     = 29;
        $createRooms->sort         = 2;
        $createRooms->created_at   = Carbon::now();
        $createRooms->updated_at   = Carbon::now();
        $createRooms->save();

        $permission_model              = config('access.permission');
        $editRooms               = new $permission_model;
        $editRooms->name         = 'edit-rooms';
        $editRooms->display_name = 'Edit Rooms';
        $editRooms->system       = true;
        $editRooms->group_id     = 29;
        $editRooms->sort         = 3;
        $editRooms->created_at   = Carbon::now();
        $editRooms->updated_at   = Carbon::now();
        $editRooms->save();

        $permission_model              = config('access.permission');
        $deleteRooms               = new $permission_model;
        $deleteRooms->name         = 'delete-rooms';
        $deleteRooms->display_name = 'Delete Rooms';
        $deleteRooms->system       = true;
        $deleteRooms->group_id     = 29;
        $deleteRooms->sort         = 4;
        $deleteRooms->created_at   = Carbon::now();
        $deleteRooms->updated_at   = Carbon::now();
        $deleteRooms->save();

        /**
         * Room Type
         */

        $permission_model                   = config('access.permission');
        $viewRoomTypeManagement               = new $permission_model;
        $viewRoomTypeManagement->name         = 'view-roomType-management';
        $viewRoomTypeManagement->display_name = 'View RoomType Management';
        $viewRoomTypeManagement->system       = true;
        $viewRoomTypeManagement->group_id     = 30;
        $viewRoomTypeManagement->sort         = 1;
        $viewRoomTypeManagement->created_at   = Carbon::now();
        $viewRoomTypeManagement->updated_at   = Carbon::now();
        $viewRoomTypeManagement->save();

        $permission_model              = config('access.permission');
        $createRoomTypes               = new $permission_model;
        $createRoomTypes->name         = 'create-roomTypes';
        $createRoomTypes->display_name = 'Create RoomTypes';
        $createRoomTypes->system       = true;
        $createRoomTypes->group_id     = 30;
        $createRoomTypes->sort         = 2;
        $createRoomTypes->created_at   = Carbon::now();
        $createRoomTypes->updated_at   = Carbon::now();
        $createRoomTypes->save();

        $permission_model              = config('access.permission');
        $editRoomTypes               = new $permission_model;
        $editRoomTypes->name         = 'edit-roomTypes';
        $editRoomTypes->display_name = 'Edit RoomTypes';
        $editRoomTypes->system       = true;
        $editRoomTypes->group_id     = 30;
        $editRoomTypes->sort         = 3;
        $editRoomTypes->created_at   = Carbon::now();
        $editRoomTypes->updated_at   = Carbon::now();
        $editRoomTypes->save();

        $permission_model              = config('access.permission');
        $deleteRoomTypes               = new $permission_model;
        $deleteRoomTypes->name         = 'delete-roomTypes';
        $deleteRoomTypes->display_name = 'Delete RoomTypes';
        $deleteRoomTypes->system       = true;
        $deleteRoomTypes->group_id     = 30;
        $deleteRoomTypes->sort         = 4;
        $deleteRoomTypes->created_at   = Carbon::now();
        $deleteRoomTypes->updated_at   = Carbon::now();
        $deleteRoomTypes->save();

        /**
         * Student BacII
         */

        $permission_model                   = config('access.permission');
        $viewStudentBac2Management               = new $permission_model;
        $viewStudentBac2Management->name         = 'view-studentBac2-management';
        $viewStudentBac2Management->display_name = 'View StudentBac2 Management';
        $viewStudentBac2Management->system       = true;
        $viewStudentBac2Management->group_id     = 31;
        $viewStudentBac2Management->sort         = 1;
        $viewStudentBac2Management->created_at   = Carbon::now();
        $viewStudentBac2Management->updated_at   = Carbon::now();
        $viewStudentBac2Management->save();

        $permission_model              = config('access.permission');
        $createStudentBac2s               = new $permission_model;
        $createStudentBac2s->name         = 'create-studentBac2s';
        $createStudentBac2s->display_name = 'Create StudentBac2s';
        $createStudentBac2s->system       = true;
        $createStudentBac2s->group_id     = 31;
        $createStudentBac2s->sort         = 2;
        $createStudentBac2s->created_at   = Carbon::now();
        $createStudentBac2s->updated_at   = Carbon::now();
        $createStudentBac2s->save();

        $permission_model              = config('access.permission');
        $editStudentBac2s               = new $permission_model;
        $editStudentBac2s->name         = 'edit-studentBac2s';
        $editStudentBac2s->display_name = 'Edit StudentBac2s';
        $editStudentBac2s->system       = true;
        $editStudentBac2s->group_id     = 31;
        $editStudentBac2s->sort         = 3;
        $editStudentBac2s->created_at   = Carbon::now();
        $editStudentBac2s->updated_at   = Carbon::now();
        $editStudentBac2s->save();

        $permission_model              = config('access.permission');
        $deleteStudentBac2s               = new $permission_model;
        $deleteStudentBac2s->name         = 'delete-studentBac2s';
        $deleteStudentBac2s->display_name = 'Delete StudentBac2s';
        $deleteStudentBac2s->system       = true;
        $deleteStudentBac2s->group_id     = 31;
        $deleteStudentBac2s->sort         = 4;
        $deleteStudentBac2s->created_at   = Carbon::now();
        $deleteStudentBac2s->updated_at   = Carbon::now();
        $deleteStudentBac2s->save();

        /**
         * Department Option
         */

        $permission_model                   = config('access.permission');
        $viewDepartmentOptionManagement               = new $permission_model;
        $viewDepartmentOptionManagement->name         = 'view-departmentOption-management';
        $viewDepartmentOptionManagement->display_name = 'View DepartmentOption Management';
        $viewDepartmentOptionManagement->system       = true;
        $viewDepartmentOptionManagement->group_id     = 32;
        $viewDepartmentOptionManagement->sort         = 1;
        $viewDepartmentOptionManagement->created_at   = Carbon::now();
        $viewDepartmentOptionManagement->updated_at   = Carbon::now();
        $viewDepartmentOptionManagement->save();

        $permission_model              = config('access.permission');
        $createDepartmentOptions               = new $permission_model;
        $createDepartmentOptions->name         = 'create-departmentOptions';
        $createDepartmentOptions->display_name = 'Create DepartmentOptions';
        $createDepartmentOptions->system       = true;
        $createDepartmentOptions->group_id     = 32;
        $createDepartmentOptions->sort         = 2;
        $createDepartmentOptions->created_at   = Carbon::now();
        $createDepartmentOptions->updated_at   = Carbon::now();
        $createDepartmentOptions->save();

        $permission_model              = config('access.permission');
        $editDepartmentOptions               = new $permission_model;
        $editDepartmentOptions->name         = 'edit-departmentOptions';
        $editDepartmentOptions->display_name = 'Edit DepartmentOptions';
        $editDepartmentOptions->system       = true;
        $editDepartmentOptions->group_id     = 32;
        $editDepartmentOptions->sort         = 3;
        $editDepartmentOptions->created_at   = Carbon::now();
        $editDepartmentOptions->updated_at   = Carbon::now();
        $editDepartmentOptions->save();

        $permission_model              = config('access.permission');
        $deleteDepartmentOptions               = new $permission_model;
        $deleteDepartmentOptions->name         = 'delete-departmentOptions';
        $deleteDepartmentOptions->display_name = 'Delete DepartmentOptions';
        $deleteDepartmentOptions->system       = true;
        $deleteDepartmentOptions->group_id     = 32;
        $deleteDepartmentOptions->sort         = 4;
        $deleteDepartmentOptions->created_at   = Carbon::now();
        $deleteDepartmentOptions->updated_at   = Carbon::now();
        $deleteDepartmentOptions->save();

        /**
         * Promotion
         */

        $permission_model                   = config('access.permission');
        $viewPromotionManagement               = new $permission_model;
        $viewPromotionManagement->name         = 'view-promotion-management';
        $viewPromotionManagement->display_name = 'View Promotion Management';
        $viewPromotionManagement->system       = true;
        $viewPromotionManagement->group_id     = 33;
        $viewPromotionManagement->sort         = 1;
        $viewPromotionManagement->created_at   = Carbon::now();
        $viewPromotionManagement->updated_at   = Carbon::now();
        $viewPromotionManagement->save();

        $permission_model              = config('access.permission');
        $createPromotions               = new $permission_model;
        $createPromotions->name         = 'create-promotions';
        $createPromotions->display_name = 'Create Promotions';
        $createPromotions->system       = true;
        $createPromotions->group_id     = 33;
        $createPromotions->sort         = 2;
        $createPromotions->created_at   = Carbon::now();
        $createPromotions->updated_at   = Carbon::now();
        $createPromotions->save();

        $permission_model              = config('access.permission');
        $editPromotions               = new $permission_model;
        $editPromotions->name         = 'edit-promotions';
        $editPromotions->display_name = 'Edit Promotions';
        $editPromotions->system       = true;
        $editPromotions->group_id     = 33;
        $editPromotions->sort         = 3;
        $editPromotions->created_at   = Carbon::now();
        $editPromotions->updated_at   = Carbon::now();
        $editPromotions->save();

        $permission_model              = config('access.permission');
        $deletePromotions               = new $permission_model;
        $deletePromotions->name         = 'delete-promotions';
        $deletePromotions->display_name = 'Delete Promotions';
        $deletePromotions->system       = true;
        $deletePromotions->group_id     = 33;
        $deletePromotions->sort         = 4;
        $deletePromotions->created_at   = Carbon::now();
        $deletePromotions->updated_at   = Carbon::now();
        $deletePromotions->save();

        /**
         * Redouble
         */

        $permission_model                   = config('access.permission');
        $viewRedoubleManagement               = new $permission_model;
        $viewRedoubleManagement->name         = 'view-redouble-management';
        $viewRedoubleManagement->display_name = 'View Redouble Management';
        $viewRedoubleManagement->system       = true;
        $viewRedoubleManagement->group_id     = 34;
        $viewRedoubleManagement->sort         = 1;
        $viewRedoubleManagement->created_at   = Carbon::now();
        $viewRedoubleManagement->updated_at   = Carbon::now();
        $viewRedoubleManagement->save();

        $permission_model              = config('access.permission');
        $createRedoubles               = new $permission_model;
        $createRedoubles->name         = 'create-redoubles';
        $createRedoubles->display_name = 'Create Redoubles';
        $createRedoubles->system       = true;
        $createRedoubles->group_id     = 34;
        $createRedoubles->sort         = 2;
        $createRedoubles->created_at   = Carbon::now();
        $createRedoubles->updated_at   = Carbon::now();
        $createRedoubles->save();

        $permission_model              = config('access.permission');
        $editRedoubles               = new $permission_model;
        $editRedoubles->name         = 'edit-redoubles';
        $editRedoubles->display_name = 'Edit Redoubles';
        $editRedoubles->system       = true;
        $editRedoubles->group_id     = 34;
        $editRedoubles->sort         = 3;
        $editRedoubles->created_at   = Carbon::now();
        $editRedoubles->updated_at   = Carbon::now();
        $editRedoubles->save();

        $permission_model              = config('access.permission');
        $deleteRedoubles               = new $permission_model;
        $deleteRedoubles->name         = 'delete-redoubles';
        $deleteRedoubles->display_name = 'Delete Redoubles';
        $deleteRedoubles->system       = true;
        $deleteRedoubles->group_id     = 34;
        $deleteRedoubles->sort         = 4;
        $deleteRedoubles->created_at   = Carbon::now();
        $deleteRedoubles->updated_at   = Carbon::now();
        $deleteRedoubles->save();

        /**
         * Log-viewer
         */

        $permission_model                   = config('access.permission');
        $viewRedoubleManagement               = new $permission_model;
        $viewRedoubleManagement->name         = 'view-log-viewer-management';
        $viewRedoubleManagement->display_name = 'View Redouble Management';
        $viewRedoubleManagement->system       = true;
        $viewRedoubleManagement->sort         = 1;
        $viewRedoubleManagement->created_at   = Carbon::now();
        $viewRedoubleManagement->updated_at   = Carbon::now();
        $viewRedoubleManagement->save();

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}