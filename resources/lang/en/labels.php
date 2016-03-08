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
        'schoolFees' => [
            'title' => 'School & Scholarship Fee Rate',
            'sub_index_title' => 'All existing fee rate',
            'sub_edit_title' => 'Edit fee rate information',
            'sub_create_title' => 'Create new fee rate',
            'sub_detail_title' => 'Detail of a fee rate',
            'fields'=>[
                'group' => "Group",
                'promotion_id' => "Promotion",
                'scholarship_id' => 'Scholarship',
                'to_pay' => 'To Pay',
                'budget' => "Award",
                'currency' => 'Currency',
                'degree_id' => 'Degree',
                'department_id' => 'Department',
                'grade_id' => 'Grade',
                'academic_year_id' => 'Academic Year',
                'description' => 'Description',
                'active' => 'Active'
            ],
            'index_tabs' => [
                'school_fee' => "School Fee Rates",
                'scholarship_fee' => "Scholarship Fee & Award"
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
        ],
        'employees' => [
            'title' => 'Employees',
            'sub_index_title' => 'All existing employees',
            'sub_edit_title' => 'Edit employee',
            'sub_create_title' => 'Register new employee',
            'sub_detail_title' => 'Detail of employee',
            'sub_import_title' => "Import employees",
            'fields'=>[
                'name_kh' => 'Name KH',
                'name_latin' => 'Name Latin',
                'phone' => "Phone",
                'email' => "Email",
                'department_id' => "Department",
                'birthdate' => "Birth Date",
                'active' => "Active",
                'address' => "Address",
                'gender_id' => "Gender",
                'user_id' => 'Related User',
                'role_id' => 'Related Role'
            ]
        ],
        'exams' => [
            'title' => 'Examinations',
            'sub_index_title' => 'All existing examinations',
            'sub_edit_title' => 'Edit examination',
            'sub_create_title' => 'Register new examination',
            'sub_detail_title' => 'Detail of an examination',
            'sub_import_title' => "Import examinations",
            'show_tabs' => [
                'general_info' => 'General Information',
                'candidate_info' => 'Candidate Information',
                'room_info' => 'Room Information',
                'staff_info' => 'Staff Information'
            ],
            'fields'=>[
                'name' => 'Exam Name',
                'date_start' => 'Date Start',
                'date_end' => 'Date End',
                'date_start_end' => "Date From/Till",
                'active' => "Class",
                'description' => "Description",
                'type_id' => "Exam Type",
                'academic_year_id' => 'Academic Year',
                'number_room_controller' => 'Number of controller/Room',
                'number_floor_controller' => 'Number of controller/Floor',
                'math_score_quote' =>  "Math Max Score",
                'phys_chem_score_quote' => "Physique/Chemistry Max Score",
                'logic_score_quote' => "Logic Max Score",
            ]
        ],
        'candidates' => [
            'title' => 'Candidates',
            'sub_index_title' => 'All existing candidates',
            'sub_edit_title' => 'Edit student',
            'sub_create_title' => 'Register new candidate',
            'sub_detail_title' => 'Detail of candidate',
            'sub_import_title' => "Import candidates",
            'fields'=>[
                'name_latin' => 'Name Latin',
                'name_kh' => 'Name Khmer',
                'register_id' => 'Register ID',
                'dob' => "Date of birth",
                'mcs_no' => "Ministry No",
                'can_id' => "Candidate ID",
                'phone' => "Phone",
                'email' => "Email",
                'address' => "Address",
                'address_current' => "Current Address",
                'is_paid' => "Paid",
                'result' => "Result",
                'register_from' => "Register From",
                'math_c' => "Math Correct",
                'math_w' => "Math Wrong",
                'math_na' => "Math No Answer",
                'phys_chem_c' => "Physique/Chemistry Correct",
                'phys_chem_w' => "Physique/Chemistry Wrong",
                'phys_chem_na' => "Physique/Chemistry No Answer",
                'logic_c' => "Logic Correct",
                'logic_w' => "Logic Wrong",
                'logic_na' => "Logic No Answer",
                'total_s' => "Total Score",
                'average' => "Average",
                'bac_percentile' => "BacII Percentile",
                'active' => "Active",
                'highschool_id' => "High School",
                'promotion_id' => "Promotion",
                'bac_total_grade' => "BacII Grade",
                'bac_math_grade' => "BacII/Math Grade",
                'bac_phys_grade' => "BacII/Physique Grade",
                'bac_chem_grade' => "BacII/Chemistry Grade",
                'bac_year' => "BacII Year",
                'province_id' => "Province",
                'pob' => "Place of Birth",
                'gender_id' => "Gender",
                'academic_year_id' => "Academic Year",
                'degree_id' => "Degree",
                'exam_id' => "Exam Date",
                'payslip_client_id' => "Payment ID"
            ]
        ],
        'reporting' => [
            'academic_year_id' => 'Academic Year',
            'degree_id' => 'Degree',
            'department_id' => 'Department',
            'all_department' => 'All Department',
        ],
        'accounting' => [

            'fields'=>[
                'amount_dollar' => "Amount ($)",
                'amount_riel' => "Amount (៛)",
                'amount_kh' => "Amount as Letters",
                'number' => 'Number',
                'account_id' => 'Account Name',
                'payslip_client_id' => 'Income From',
                'date' => 'Date of payment'
            ]
        ],
        'incomes' => [
            'title' => 'Incomes',
            'sub_index_title' => 'All existing income',
            'sub_edit_title' => 'Edit income information',
            'sub_create_title' => 'Create new income',
            'sub_detail_title' => 'Detail of an income',
        ],
        'outcomes' => [
            'title' => 'Outcomes',
            'sub_index_title' => 'All existing outcome',
            'sub_edit_title' => 'Edit outcome information',
            'sub_create_title' => 'Create new outcome',
            'sub_detail_title' => 'Detail of an outcome',
            'search_client' => 'Search for client',
            'search_result' =>'Search result',
            'fields' => [
                'is' => "Is",
                'name' => 'Name',
            ]
        ],
        'customers' => [
            'title' => 'Customers',
            'sub_index_title' => 'All existing customer',
            'sub_edit_title' => 'Edit customer information',
            'sub_create_title' => 'Create new customer',
            'sub_detail_title' => 'Detail of an customer',
            'search_client' => 'Search for client',
            'search_result' =>'Search result',
            'fields' => [
                'name' => "Name",
                'address' => 'Address',
                'phone' => 'Phone',
                'email' => 'Email',
                'company' => 'Company',
                'identity_number' => 'Identity Number',
                'active' => 'Active'
            ]
        ],
        'courseAnnuals' => [
            'title' => 'Course Annually',
            'sub_index_title' => 'All existing course',
            'sub_edit_title' => 'Edit course information',
            'sub_create_title' => 'Create new course',
            'sub_detail_title' => 'Detail of an course',
            'fields' => [
                'name' => "Name",
                'semester' => 'Semester',
                'academic_year_id' => 'Academic year',
                'department_id' => 'Department',
                'degree_id' => 'Degree',
                'grade_id' => 'Grade',
                'employee_id' => 'Lecturer'
            ]
        ],
        'coursePrograms' => [
            'title' => 'Course Programs',
            'sub_index_title' => 'All existing course program',
            'sub_edit_title' => 'Edit course program information',
            'sub_create_title' => 'Create new course program',
            'sub_detail_title' => 'Detail of a course program',
            'fields' => [
                'name_kh' => "Name Khmer",
                'name_en' => 'Name English',
                'name_fr' => 'Name French',
                'duration' => 'Duration',
                'code' => 'Code',
            ]
        ],
        'scholarships' => [
            'title' => 'Scholarships',
            'sub_index_title' => 'All existing scholarships',
            'sub_edit_title' => 'Edit scholarship information',
            'sub_create_title' => 'Create new scholarship',
            'sub_detail_title' => 'Detail of a scholarship',
            'general_information' => 'General Information',
            'more_information' => 'More Information',
            'school_fee_and_award' => 'School Fee and Award',
            'scholarship_holder' => 'Scholarship Holder',
            'fields' => [
                'name_kh' => "Name Khmer",
                'name_en' => 'Name English',
                'name_fr' => 'Name French',
                'start' => 'Date Start',
                'stop' => 'Date Stop',
                'founder' => 'Founder',
                'code' => 'Code',
                'description' => 'Description',
                'date_start_end' => 'Date From/Till',
                'active' => 'Active',
                'isDroppedUponFail' => 'Suspend upon student fail',
                'duration' => 'Scholarship period'
            ],
            'school_fee_and_award_tab' => [
                'degree' => 'Degree',
                'promotion' => 'Promotion',
                'school_fee_rate' =>'School Fee Rate',
                'scholarship_budget' =>'Scholarship Budget',
            ],
            'scholarship_holder_tab' => [
                'id_card' => 'ID Card',
                'name_kh' => 'Name Khmer',
                'name_latin' => 'Name Latin',
                'dob' => 'Date of Birth',
                'gender' => 'Gender',
                'class' => 'Class'
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
