<?php

return [

    /*
     * Users table used to store users
     */
    'users_table' => 'users',

    /*
     * Role model used by Access to create correct relations. Update the role if it is in a different namespace.
    */
    'role' => App\Models\Access\Role\Role::class,

    /*
     * Roles table used by Access to save roles to the database.
     */
    'roles_table' => 'roles',

    /*
     * Permission model used by Access to create correct relations.
     * Update the permission if it is in a different namespace.
     */
    'permission' => App\Models\Access\Permission\Permission::class,

    /*
     * Permissions table used by Access to save permissions to the database.
     */
    'permissions_table' => 'permissions',

    /*
     * PermissionGroup model used by Access to create permissions groups.
     * Update the group if it is in a different namespace.
     */
    'group' => App\Models\Access\Permission\PermissionGroup::class,

    /*
     * Permissions table used by Access to save permissions to the database.
     */
    'permission_group_table' => 'permission_groups',

    /*
     * permission_role table used by Access to save relationship between permissions and roles to the database.
     */
    'permission_role_table' => 'permission_role',

    /*
     * permission_user table used by Access to save relationship between permissions and users to the database.
     * This table is only for permissions that belong directly to a specific user and not a role
     */
    'permission_user_table' => 'permission_user',

    /*
     * Table that specifies if one permission is dependent on another.
     * For example in order for a user to have the edit-user permission they also need the view-backend permission.
     */
    'permission_dependencies_table' => 'permission_dependencies',

    /*
     * PermissionDependency model used by Access to create permissions dependencies.
     * Update the dependency if it is in a different namespace.
     */
    'dependency' => App\Models\Access\Permission\PermissionDependency::class,

    /*
     * assigned_roles table used by Access to save assigned roles to the database.
     */
    'assigned_roles_table' => 'assigned_roles',

    /*
     * Configurations for the user
     */
    'users' => [
        /*
         * Administration tables
         */
        'default_per_page' => 25,

        /*
         * The role the user is assigned to when they sign up from the frontend, not namespaced
         */
        'default_role' => 'User',
        //'default_role' => 2,

        /*
         * Whether or not the user has to confirm their email when signing up
         */
        'confirm_email' => false,

        /*
         * Whether or not the users email can be changed on the edit profile screen
         */
        'change_email' => false,
    ],

    'schools' => [
        'itc' => 2,
    ],
    'departments' =>[
        'department_gca'=> 1,
        'department_gci'=> 2,
        'department_gee'=> 3,
        'department_gic'=> 4,
        'department_gim'=> 5,
        'department_gru'=> 6,
        'department_ggg'=> 7,
        'department_tc'=> 8,
        'department_finance' => 9,
        'department_study_affair'=>10,
        'department_academic'=>11,
        'sa'=> 12,
        'sf'=> 13
    ],
    'genders' => [
        'gender_male'=> 1,
        'gender_female'=> 2,
    ],
    'degrees' => [
        'degree_engineer'=> 1,
        'degree_associate'=> 2,
        'degree_bachelor'=> 3,
        'degree_master'=>4,
        'degree_doctor'=> 5,
    ],
    'grades' => [
        'grade_1'=> 1,
        'grade_2'=> 2,
        'grade_3'=> 3,
        'grade_4'=> 4,
        'grade_5'=> 5,
    ],
    'income_type'=> [
        'income_type_student_day'=>1,
        'income_type_student_night'=>2,
        'income_type_student_master'=>3,
    ],

    'account'=>[
        'account_day_student'=>1,
        'account_night_student'=>2,
        'account_master_student'=>3,
    ],
    'exam' => [
        'entrance_engineer' => 1,
        'entrance_dut' => 2,
        'final_semester' => 3
    ],

    /*
     * Configuration for roles
     */
    'roles' => [
        /*
         * Whether a role must contain a permission or can be used standalone as a label
         */
        'role_must_contain_permission' => true
    ],

    /*
     * Socialite session variable name
     * Contains the name of the currently logged in provider in the users session
     * Makes it so social logins can not change passwords, etc.
     */
    'socialite_session_name' => 'socialite_provider',

    /**
     * Timetable Constants
     */
    'timetable' => [
        'department_id' => [
            'sa' => 12,
            'sf' => 13
        ]
    ],
];