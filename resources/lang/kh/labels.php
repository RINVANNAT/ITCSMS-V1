<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Labels Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in labels throughout the system.
    | Regardless where it is placed, a label can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'general' => [
        'all' => 'All',
        'yes' => 'Yes',
        'no' => 'No',
        'actions' => 'Actions',
        'buttons' => [
            'save' => 'Save',
            'update' => 'Update',
        ],
        'hide' => 'Hide',
        'none' => 'None',
        'show' => 'Show',
        'toggle_navigation' => 'Toggle Navigation',
    ],

    'backend' => [
        'access' => [
            'permissions' => [
                'create' => 'Create Permission',
                'dependencies' => 'Dependencies',
                'edit' => 'Edit Permission',

                'groups' => [
                    'create' => 'Create Group',
                    'edit' => 'Edit Group',

                    'table' => [
                        'name' => 'Name',
                    ],
                ],

                'grouped_permissions' => 'Grouped Permissions',
                'label' => 'permissions',
                'management' => 'Permission Management',
                'no_groups' => 'There are no permission groups.',
                'no_permissions' => 'No permission to choose from.',
                'no_roles' => 'No Roles to set',
                'no_ungrouped' => 'There are no ungrouped permissions.',

                'table' => [
                    'dependencies' => 'Dependencies',
                    'group' => 'Group',
                    'group-sort' => 'Group Sort',
                    'name' => 'Name',
                    'permission' => 'Permission',
                    'roles' => 'Roles',
                    'system' => 'System',
                    'total' => 'permission total|permissions total',
                    'users' => 'Users',
                ],

                'tabs' => [
                    'general' => 'General',
                    'groups' => 'All Groups',
                    'dependencies' => 'Dependencies',
                    'permissions' => 'All Permissions',
                ],

                'ungrouped_permissions' => 'Ungrouped Permissions',
            ],

            'roles' => [
                'create' => 'Create Role',
                'edit' => 'Edit Role',
                'management' => 'Role Management',

                'table' => [
                    'number_of_users' => 'Number of Users',
                    'permissions' => 'Permissions',
                    'role' => 'Role',
                    'sort' => 'Sort',
                    'total' => 'role total|roles total',
                ],
            ],

            'users' => [
                'active' => 'Active Users',
                'all_permissions' => 'All Permissions',
                'change_password' => 'Change Password',
                'change_password_for' => 'Change Password for :user',
                'create' => 'Create User',
                'deactivated' => 'Deactivated Users',
                'deleted' => 'Deleted Users',
                'dependencies' => 'Dependencies',
                'edit' => 'Edit User',
                'management' => 'User Management',
                'no_other_permissions' => 'No Other Permissions',
                'no_permissions' => 'No Permissions',
                'no_roles' => 'No Roles to set.',
                'permissions' => 'Permissions',
                'permission_check' => 'Checking a permission will also check its dependencies, if any.',

                'table' => [
                    'confirmed' => 'Confirmed',
                    'created' => 'Created',
                    'email' => 'E-mail',
                    'id' => 'ID',
                    'last_updated' => 'Last Updated',
                    'name' => 'Name',
                    'no_deactivated' => 'No Deactivated Users',
                    'no_deleted' => 'No Deleted Users',
                    'other_permissions' => 'Other Permissions',
                    'roles' => 'Roles',
                    'total' => 'user total|users total',
                ],
            ],
        ],























































































































































































































































































































































        'exams' => [
            'title' => 'Examinations',
            'sub_index_title' => 'All existing examinations',
            'sub_edit_title' => 'Edit examination',
            'sub_create_title' => 'Register new examination',
            'sub_detail_title' => 'Detail of an examination',
            'sub_import_title' => "Import examinations",
            'secret_code' => [
                'title' => 'Secret Code',
                'print' => 'Print Secret Codes',
                'export' => 'Export Secret Codes',
                'generate_auto' => 'Auto Generate',
                'generate_manual' => 'Manually Enter'
            ],
            'course' => [
                'add_course' => 'Add new course for exam',
                'edit_course' => 'Edit course for exam',
                'choose_course' => 'Choose courses for exam',
                'view_score' => 'View all score',
                'fields' => [
                    "course_name" => "Course Name",
                    "total_question" => "Total Questions",
                    "description" => "Description",
                    "roomcode" => "Room Code",
                    "order" => "Order",
                    "correct" => "Correct",
                    "wrong" => "Wrong",
                    'na' => "No Answer",
                    "sequence" => "Sequence",
                    "corrector" => "Corrector",
                    "register" => "Register User"
                ]
            ],
            'exam_room' => [
                'title' => [
                    'merge' => 'Merge Room',
                    'split' => 'Split Room',
                    'add' => 'Add Room',
                    'edit' => 'Edit Room'
                ],
                'field' => [
                    'name' => 'Name',
                    'capacity' => 'Capacity',
                    'Building' => 'Building'
                ]
            ],
            'show_tabs' => [
                'general_info' => 'General Information',
                'candidate_info' => 'Candidate Information',
                'course_info' => 'Course Information',
                'room_info' => 'Room Information',
                'staff_info' => 'Staff Information',
                'download' => 'Download'
            ],
            'fields'=>[
                'name' => 'Exam Name',
                'date_start' => 'Date Start',
                'date_end' => 'Date End',
                'date_start_end' => "Date From/Till",
                'success_registration_date_start_end' => "Success Registration",
                'reserve_registration_date_start_end' => "Reserve Registration",
                'active' => "Class",
                'description' => "Description",
                'type_id' => "Exam Type",
                'academic_year_id' => 'Academic Year',
                'number_room_controller' => 'Number of controller/Room',
                'number_floor_controller' => 'Number of controller/Floor',
                'math_score_quote' =>  "Math Max Score",
                'phys_chem_score_quote' => "Physique/Chemistry Max Score",
                'logic_score_quote' => "Logic Max Score",
            ],
            'chart' => [
                'export_image' => 'ទាញយករូបភាព',
                'department' => 'ដេបា៉តឺម៉ង់',
                'engineer_statistic' => [
                    'candidate_register' => 'ការចុះឈ្មោះ បេក្ខជន វិស្វ័ករ',
                    'no_data'   => 'មិនទាន់មាន ការចុះឈ្មោះ បេក្ខជនប្រលង',
                    'yaxis'     => 'ចំនួនបេក្ខជន',
                    'btn_candidate_registration' => 'ស្ថិតិ នៃការីចុះឈ្មោះ បេក្ខជន',
                    'btn_attendence_list' =>'បញ្ជីវត្តមាន បេក្ខជន',
                    'student_engineer' => 'ការចុះឈ្មោះនិសិត្សថ្នាក់វិស្វ័ករ',
                    'student_statistic' => 'ស្ថិតិ នៃការចុះឈ្មោះ និសិត្សថ្នាក់វិស្វ័ករ',
                    'no_student_registration'=> 'មិនទាន់មាន ការចុះឈ្មោះនិសិត្សថ្នាក់វិស្វ័ករ',
                    'result_candidate_engineer' => 'លទ្ធផលសំរាប់បេក្ខជនវិស្វ័ករ',
                    'result_list'=> 'បញ្ជីលទ្ធផល់',
                    'result_statistic' => 'ស្ថិតិនៃលទ្ធផលបេក្ខជនវិស្វ័ករ',
                    'no_result' => 'មិនទាន់មានការបិតផ្សាយលទ្ធផល់',
                    'total_pass' => 'សិស្សជាប់សរុប',
                    'pass_female' => 'សិស្សស្រីជាប់សរុប',
                    'total_reserve' => 'សិស្សបំរុងសរុប',
                    'reserve_female' => 'សិស្សស្រីបំរុងសរុប',
                    'grade' => 'និទ្ទេស',
                    'total_student' => 'ចំនួនសិស្សសរុប',
                    'total_female' => 'ចំនួនសិស្សស្រីសរុប',
                ],
                'dut_statistic' => [
                    'candidate_dut_register' => 'ការចុះឈ្មោះ បេក្ខជន ផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្ម',
                    'no_data'   => 'មិនទាន់មាន ការចុះឈ្មោះ បេក្ខជនផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្ម',
                    'btn_dut_statistic' => 'ស្ថិតិបេក្ខជន ផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្ម',
                    'btn_register_list' =>'បញ្ជីចុះឈ្មោ៖ បេក្ខជន ផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្ម',

                    'student_dut_registration_statistic' => 'ការចុះឈ្មោះនិសិត្សផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្ម',
                    'student_dut_statistic' => 'ស្ថិតិ នៃការចុះឈ្មោះ និសិត្សផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្ម',
                    'no_student_registration'=> 'មិនទាន់មាន ការចុះឈ្មោះនិសិត្សផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្មរ',

                    'result_candidate_dut' => 'លទ្ធផលសំរាប់បេក្ខជនផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្មរ',
                    'dut_result_list'=> 'បញ្ជីលទ្ធផល់',
                    'dut_result_statistic' => 'ស្ថិតិនៃលទ្ធផលបេក្ខជនផ្នែកបរិញ្ញាប័ត្ររងវិស្វកម្ម',
                    'no_result' => 'មិនទាន់មានការបិតផ្សាយលទ្ធផល់',
                    'male' => 'ប្រុស',
                    'female' => 'ស្រី'

                ]
            ]
        ],

    ],

    'frontend' => [

        'auth' => [
            'login_box_title' => 'Login',
            'login_button' => 'Login',
            'login_with' => 'Login with :social_media',
            'register_box_title' => 'Register',
            'register_button' => 'Register',
            'remember_me' => 'Remember Me',
        ],

        'passwords' => [
            'forgot_password' => 'Forgot Your Password?',
            'reset_password_box_title' => 'Reset Password',
            'reset_password_button' => 'Reset Password',
            'send_password_reset_link_button' => 'Send Password Reset Link',
        ],

        'macros' => [
            'country' => [
                'alpha' => 'Country Alpha Codes',
                'alpha2' => 'Country Alpha 2 Codes',
                'alpha3' => 'Country Alpha 3 Codes',
                'numeric' => 'Country Numeric Codes',
            ],

            'macro_examples' => 'Macro Examples',

            'state' => [
                'mexico' => 'Mexico State List',
                'us' => [
                    'us' => 'US States',
                    'outlying' => 'US Outlying Territories',
                    'armed' => 'US Armed Forces',
                ],
            ],

            'territories' => [
                'canada' => 'Canada Province & Territories List',
            ],

            'timezone' => 'Timezone',
        ],

        'user' => [
            'passwords' => [
                'change' => 'Change Password',
            ],

            'profile' => [
                'avatar' => 'Avatar',
                'created_at' => 'Created At',
                'edit_information' => 'Edit Information',
                'email' => 'E-mail',
                'last_updated' => 'Last Updated',
                'name' => 'Name',
                'update_information' => 'Update Information',
            ],
        ],

    ],
];
