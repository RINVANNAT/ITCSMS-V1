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
        'id' => 'ID',
        'last_updated' => 'Last Updated'
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
        'departments' => [
            'title' => 'Departments',
            'sub_index_title' => 'All available departments',
            'sub_edit_title' => 'Edit department',
            'sub_create_title' => 'Create department',
            'sub_detail_title' => 'Detail of department',
            'fields'=>[
                'code' => 'Code',
                'name_kh' => 'Name KH',
                'name_en' => "Name EN",
                'name_fr' => "Name FR",
                'description' => "Description",
                "parent" => "Head Department",
                'is_specialist' => "is Specialist",
                'school' => "School"
            ]
        ],
        'degrees' => [
            'title' => 'Academic Degrees',
            'sub_index_title' => 'All available academic degrees',
            'sub_edit_title' => 'Edit degree',
            'sub_create_title' => 'Create new academic degree',
            'sub_detail_title' => 'Detail of an academic degree',
            'fields'=>[
                'code' => 'Code',
                'name_kh' => 'Name KH',
                'name_en' => "Name EN",
                'name_fr' => "Name FR",
                'school' => "school",
                'departments' => "Related Departments",
                'description' => "Description"
            ]
        ],
        'grades' => [
            'title' => 'Grades',
            'sub_index_title' => 'All available grades',
            'sub_edit_title' => 'Edit grade',
            'sub_create_title' => 'Create grade',
            'sub_detail_title' => 'Detail of grade',
            'fields'=>[
                'code' => 'Code',
                'name_kh' => 'Name KH',
                'name_en' => "Name EN",
                'name_fr' => "Name FR"
            ]
        ],
        'academicYears' => [
            'title' => 'Academic Years',
            'sub_index_title' => 'All available academic years',
            'sub_create_title' => 'Create academic year',
            'sub_edit_title' => 'Edit academic year',
            'sub_detail_title' => 'Detail of academic year',
            'fields'=>[
                'code' => 'Code',
                'name_kh' => 'Name KH',
                'name_latin' => 'Name Latin',
                'date_start' => "Date Start",
                'date_end' => "Date End",
                'date_start_end' => "Date From/Until",
                'description' => "Description"
            ]
        ],
        'accounts' => [
            'title' => 'Account',
            'sub_index_title' => 'All available account',
            'sub_edit_title' => 'Edit account',
            'sub_create_title' => 'Create new account',
            'sub_detail_title' => 'Detail of an account',
            'fields'=>[
                'name' => 'Name',
                'active' => 'Is Active',
                'description' => "Description",
                'amount_dollar' => 'Amount in Dollar',
                'amount_riel' => 'Amount in Riel',
            ]
        ],

        'buildings' => [
            'title' => 'Building',
            'sub_index_title' => 'All existing building',
            'sub_edit_title' => 'Edit building information',
            'sub_create_title' => 'Create new building',
            'sub_detail_title' => 'Detail of an building',
            'fields'=>[
                'name' => 'Name',
                'description' => "Description",
            ]
        ],

        'incomeTypes' => [
            'title' => 'Income Type',
            'sub_index_title' => 'All existing income type',
            'sub_edit_title' => 'Edit income type information',
            'sub_create_title' => 'Create new income type',
            'sub_detail_title' => 'Detail of an income type',
            'fields'=>[
                'name' => 'Name',
                'active' => 'Is Active',
                'description' => "Description",
            ]
        ],

        'outcomeTypes' => [
            'title' => 'Outcome Type',
            'sub_index_title' => 'All existing outcome type',
            'sub_edit_title' => 'Edit outcome type information',
            'sub_create_title' => 'Create new outcome type',
            'sub_detail_title' => 'Detail of an outcome type',
            'fields'=>[
                'code' => "Code",
                'origin' => "Origin",
                'name' => 'Name',
                'active' => 'Is Active',
                'description' => "Description",
            ]
        ],
        'rooms' => [
            'title' => 'Room',
            'sub_index_title' => 'All existing rooms',
            'sub_edit_title' => 'Edit room information',
            'sub_create_title' => 'Create new rooms',
            'sub_detail_title' => 'Detail of a room',
            'fields'=>[
                'active' => 'Is Active',
                'name' => "Name",
                'nb_desk' => "Total Desk",
                'nb_chair' => 'Total Chair',
                'nb_chair_exam' => 'Chair Exam',
                'size' => 'Size',
                'room_type_id' => 'Room Type',
                'building_id' => 'Building',
                'department_id' => 'Department',
                'description' => "Description",
            ]
        ],

        'studentBac2s' => [
            'title' => 'Student Bac II',
            'sub_index_title' => 'All existing student Bac II',
            'sub_edit_title' => 'Edit student Bac II information',
            'sub_create_title' => 'Create new student Bac II',
            'sub_detail_title' => 'Detail of a student Bac II',
            'fields'=>[
                'active' => 'Is Active',
                'can_id' => "Candidate ID",
                'mcs_no' => "Ministry No",
                'province_id' => "From Province",
                'name_kh' => "Name Khmer",
                'dob' => "Date of birth",
                'gender_id' => "Gender",
                'father_name' => "Father Name",
                'mother_name' => "Mother Name",
                'pob' => "Place of birth",
                'highschool_id' => "High School",
                'room' => "Exam Room",
                'seat' => "Exam Seat",
                'bac_math_grade' => "Math Grade",
                'bac_chem_grade' => "Chemie Grade",
                'bac_phys_grade' => "Physique Grade",
                'percentile' => "Percentile",
                'grade' => "Total Grade",
                'program' => "Program",
                'desc' => "Description",
                'bac_year' => "BacII Year",
                'status' => "Status",
                'is_registered' => "Is Registered"
            ]
        ],

        'highSchools' => [
            'title' => 'High School',
            'sub_index_title' => 'All existing High School',
            'sub_edit_title' => 'Edit High School information',
            'sub_create_title' => 'Create new High School',
            'sub_detail_title' => 'Detail of an High School',
            'sub_import_title' => 'Import high schools',
            'fields'=>[
                'name_en' => 'Name Latin',
                'description' => "Description",
                'name_kh' => 'Name Khmer',
                'province_id' => 'In Province',
                'd_id' => 'District ID',
                'c_id' => 'Country ID',
                'v_id' => 'V ID',
                's_id' => 'S ID',
                'ps_id' => 'PS ID',
                'prefix_id' => 'Prefix ID',
                'valid' => 'Is Valid',
                'is_no_school' => "Is'nt School",
                'locp_code' => 'LOCP Code',
                'locd_code' => 'LOCD Code',
                'locc_code' => 'LOCC Code',
                'locv_code' => 'LOCV Code',
            ]
        ],

        'students' => [
            'title' => 'Students',
            'sub_index_title' => 'All existing students',
            'sub_edit_title' => 'Edit student',
            'sub_create_title' => 'Register new student',
            'sub_detail_title' => 'Detail of student',
            'sub_import_title' => "Import students",
            'fields'=>[
                'id_card' => 'ID Card',
                'name_kh' => 'Name KH',
                'name_latin' => 'Name Latin',
                'class' => "Class",
            ]
        ]

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
