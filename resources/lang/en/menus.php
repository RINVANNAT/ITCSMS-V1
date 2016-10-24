<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Menus Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in menu items throughout the system.
    | Regardless where it is placed, a menu item can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'backend' => [
        'access' => [
            'title' => 'Access Management',

            'permissions' => [
                'all' => 'All Permissions',
                'create' => 'Create Permission',
                'edit' => 'Edit Permission',
                'groups' => [
                    'all' => 'All Groups',
                    'create' => 'Create Group',
                    'edit' => 'Edit Group',
                    'main' => 'Groups',
                ],
                'main' => 'Permissions',
                'management' => 'Permission Management',
            ],

            'roles' => [
                'all' => 'All Roles',
                'create' => 'Create Role',
                'edit' => 'Edit Role',
                'management' => 'Role Management',
                'main' => 'Roles',
            ],

            'users' => [
                'all' => 'All Users',
                'change-password' => 'Change Password',
                'create' => 'Create User',
                'deactivated' => 'Deactivated Users',
                'deleted' => 'Deleted Users',
                'edit' => 'Edit User',
                'main' => 'Users',
            ],
        ],

        'log-viewer' => [
            'main' => 'Log Viewer',
            'dashboard' => 'Dashboard',
            'logs' => 'Logs',
        ],

        'configuration' => [
            'main' => 'Configuration',
            'departments'=> 'Departments',
            'grades'=> 'Grades',
            'degrees' => 'Degrees',
            'academicYears' => 'Academic Years',
            'course'=>"Course",
            'highSchools' => 'High School',
            'accounts' => 'Account',
            'incomeTypes' => 'Income Type',
            'outcomeTypes' => 'Outcome Type',
            'buildings' => 'Building',
            'rooms' => 'Room',
            'studentBac2s' => 'Student Bac II - Last Year',
            'studentBac2OldRecords' => 'Student Bac II - Old Records',
            'candidatesFromMoeys'=>'Student Bac II - Moeys',
            'examTypes' => 'Exam Types',
            'departmentOptions' => 'Department Option / Technique',
            'origins' => 'Room',
            'promotions' => 'Promotion',
            'schoolFees' => 'School & Scholarship Fee',
            'roomTypes' => 'Room Type',
            'redoubles' => 'Redouble'

        ],

        'reporting' => [
            'title' => 'Reporting',
            'report_student_age' => 'Students/Age Statistic',
            'report_student_redouble' => 'Students/Redouble Statistic',
            'report_student_degree' => 'Students/Techniques Statistic',
        ],

        'error' => [
            'reporting' => 'System Reporting',
        ],
        'student' => [
            'title' => 'Student',
            'generate_group' => 'Generate groups',
            'generate_id_card' => 'Generate ID Cards',
            'print_id_card' => 'Print ID Cards'
        ],
        'accounting' => [
            'title' => 'Accounting',
            'incomes' => 'Income',
            'outcomes' => 'Outcome',
            'customers' => 'Customer',
            'student_payments' => 'Student Payment',
            'candidate_payments' => 'Candidate Payment'
        ],
        'course' => [
            'title' => 'Course Management',
            'course_annuals' => 'Courses Annually',
            'course_programs' => 'Courses Program'
        ],
        'score' => [
            'title' => 'Score Management',
            'score' => 'Score',
            'input' => 'Input Student Score',
            'ranking' => 'View Student Ranking'
        ],
        'absences' => [
            'title' => 'Absence Management',
            'absences' => 'Absences',
            'input'=>'Input Student Absences'
        ],
        'candidate' => [
            'title' => 'Candidate',
        ],
        'employee' => [
            'title' => 'Employee',
        ],
        'scholarship' => [
            'title' => 'Scholarship',
        ],
        'exam' => [
            'title' => 'Examination',
            'entrances-engineer' => 'Entrance-Engineer Exams',
            'entrances-dut' => 'Entrance-DUT Exams',
            'finals' => 'Final Semester Exams'
        ],
        'scholarship' => [
            'title' => 'Scholarship',
        ],

        'sidebar' => [
            'dashboard' => 'Dashboard',
            'general' => 'General',
        ],
    ],

    'language-picker' => [
        'language' => 'Language',
        /**
         * Add the new language to this array.
         * The key should have the same language code as the folder name.
         * The string should be: 'Language-name-in-your-own-language (Language-name-in-English)'.
         * Be sure to add the new language in alphabetical order.
         */
        'langs' => [
            'de' => 'German',
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'it' => 'Italian',
            'pt-BR' => 'Brazilian Portuguese',
            'sv' => 'Swedish',
        ],
    ],
];
