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
        'last_updated' => 'Last Updated',
        'number' => 'No.'
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
            'fields' => [
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
            'fields' => [
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
            'fields' => [
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
            'fields' => [
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
            'fields' => [
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
            'sub_import_title' => 'Import buildings',
            'fields' => [
                'name' => 'Name',
                'code' => 'Code',
                'description' => "Description",
            ]
        ],

        'incomeTypes' => [
            'title' => 'Income Type',
            'sub_index_title' => 'All existing income type',
            'sub_edit_title' => 'Edit income type information',
            'sub_create_title' => 'Create new income type',
            'sub_detail_title' => 'Detail of an income type',
            'fields' => [
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
            'sub_import_title' => 'Import outcome types',
            'fields' => [
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
            'fields' => [
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
            'sub_create_title' => 'Create new room',
            'sub_detail_title' => 'Detail of a room',
            'sub_import_title' => 'Import rooms',
            'fields' => [
                'active' => 'Is Active',
                'public' => 'Is public',
                'exam' => 'Is exam',
                'name' => 'Name',
                'nb_desk' => 'Total Desk',
                'nb_chair' => 'Total Chair',
                'nb_chair_exam' => 'Chair Exam',
                'size' => 'Size',
                'room_type_id' => 'Room Type',
                'building_id' => 'Building',
                'department_id' => 'Department',
                'description' => 'Description',
                'is_exam_room' => 'Is an Exam Room',
                'is_public_room' => 'Is a Public Room'
            ]
        ],
        'roomTypes' => [
            'title' => 'Room',
            'sub_index_title' => 'All existing room Types',
            'sub_edit_title' => 'Edit room type information',
            'sub_create_title' => 'Create new room type',
            'sub_detail_title' => 'Detail of a room type',
            'sub_import_title' => 'Import room types',
            'fields' => [
                'id' => 'ID',
                'active' => 'Is Active',
                'name' => "Name",
            ]
        ],

        'redoubles' => [
            'title' => 'Redouble',
            'sub_index_title' => 'All existing redoubes',
            'sub_edit_title' => 'Edit redouble information',
            'sub_create_title' => 'Create new redouble',
            'sub_detail_title' => 'Detail of a redouble',
            'fields' => [
                'id' => 'ID',
                'active' => 'Is Active',
                'name_kh' => "Name Khmer",
                'name_en' => 'Name English',
                'name_fr' => "name French"
            ]
        ],

        'studentBac2s' => [
            'title' => 'Student Bac II',
            'sub_index_title' => 'All existing student Bac II',
            'sub_edit_title' => 'Edit student Bac II information',
            'sub_create_title' => 'Create new student Bac II',
            'sub_detail_title' => 'Detail of a student Bac II',
            'fields' => [
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
                'bac_math_grade' => "Math",
                'bac_chem_grade' => "Chem",
                'bac_phys_grade' => "Phys",
                'percentile' => "Percentile",
                'grade' => "Total",
                'program' => "Program",
                'desc' => "Description",
                'bac_year' => "BacII Year",
                'status' => "Status",
                'is_registered' => "Is Registered",
                'bac2_score_grade' => "BacII Score/Grade",
                'origin' => 'Origin'
            ]
        ],
        'studentBac2OldRecords' => [
            'title' => 'Student Bac II - Old Records',
            'sub_index_title' => 'All existing student Bac II - Old Records',
            'sub_edit_title' => 'Edit student Bac II information - Old Records',
            'sub_create_title' => 'Create new student Bac II - Old Records',
            'sub_detail_title' => 'Detail of a student Bac II - Old Records',
        ],

        'highSchools' => [
            'title' => 'High School',
            'sub_index_title' => 'All existing High School',
            'sub_edit_title' => 'Edit High School information',
            'sub_create_title' => 'Create new High School',
            'sub_detail_title' => 'Detail of an High School',
            'sub_import_title' => 'Import high schools',
            'fields' => [
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
            'sub_print_title' => "Print ID Card",
            'basic_info' => 'Basic Information',
            'more_info' => 'More Information',
            'fields' => [
                'id_card' => 'ID Card',
                'name' => 'Name',
                'name_kh' => 'Name KH',
                'name_latin' => 'Name Latin',
                'printed_transcript' => 'Printed Transcript',
                'class' => "Class",
                'photo' => 'Photo',
                'gender_id' => 'Gender',
                'pob' => 'Place of Birth',
                'dob' => 'Date of Birth',
                'radie' => 'Radie',
                'observation' => 'Observation',
                'active' => 'Active',
                'origin_id' => 'Origin',
                'phone' => 'Phone Number',
                'email' => 'Email',
                'current_address' => 'Current Address',
                'permanent_address' => 'Permanent Address',
                'parent_occupation' => 'Parent Occupation',
                'parent_name' => 'Parent Name',
                'parent_address' => 'Parent Address',
                'parent_phone' => 'Parent Phone Number',
                'mcs_no' => 'MCS No',
                'can_id' => "Can ID",
                'promotion_id' => 'Promotion',
                'degree_id' => 'Degree',
                'grade_id' => 'Grade',
                'department_id' => 'Department',
                'history_id' => 'History',
                'scholarship_id' => 'Scholarships',
                'highschool_id' => 'High School',
                'academic_year_id' => 'Academic Year',
                'redouble_id' => 'Redouble',
                'group' => 'Group',
                'department_option_id' => 'Option',
                'to_pay' => 'To Pay',
                'debt' => 'Debt'

            ],
            'tabs' => [
                'general_information' => "General Information",
                'new_academic_information' => "New Academic Information",
                'contact_information' => "Contact Information",
                'parent_information' => "Parent Information",
                'high_school_information' => "High School Information"
            ]
        ],
        'employees' => [
            'title' => 'Employees',
            'sub_index_title' => 'All existing employees',
            'sub_edit_title' => 'Edit employee',
            'sub_create_title' => 'Register new employee',
            'sub_detail_title' => 'Detail of employee',
            'sub_import_title' => "Import employees",
            'fields' => [
                'name_kh' => 'Name KH',
                'name_latin' => 'Name Latin',
                'phone' => "Phone",
                'email' => "Email",
                'contact' => "Contact",
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
            'fields' => [
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
                'math_score_quote' => "Math Max Score",
                'phys_chem_score_quote' => "Physique/Chemistry Max Score",
                'logic_score_quote' => "Logic Max Score",
            ],

            //------vannat

            'score' => [
                'input_score' => 'Input Score',
                'request_score_form' => 'Request Input Score Form',
                'select_subject' => 'Select Subject:',
                'correction_1' => 'Correction 1',
                'correction_2' => 'Correction 2',
                'Logic' => 'Logic',
                'Mathematics' => 'Mathematics',
                'Physic/Chemistry' => ' Physic/Chemistry',
                'btn_ok' => 'OK',
                'btn_cancel' => 'Cancel',
                'select_room' => 'Select Room Code :',

                'form_score' => [
                    'form_input_score' => 'Form Input Candidate Score',
                    'room_code' => 'ROOM CODE',
                    'scoring_sheet' => 'SCORING SHEET',
                    'correction' => 'CORRECTION',
                    'corrector_name' => 'CORRECTOR NAME: ',
                    'order' => 'Order',
                    'correct' => 'Correct',
                    'wrong' => 'Wrong',
                    'no_answer' => 'No Answer'
                ],
                'message' => [
                    'save_success' => 'your record have been save!',
                    'save_duplicate' => 'Duplicate record!',
                    'corrector_missing' => 'Please add the corrector name',
                    'zero_value' => 'You have inputted 0 Value!!!',

                ]
            ],

            'result' => [
                'generate_result' => 'Generate Result'
            ],

            'engineer' => [
                'engineer_entrance_exam' => 'Engineer Entrance Exam',

            ],
            'dut' => [
                'candidate_dut' => 'Candidate DUT Selection',
            ],
            'academic_year' => 'Academic Year',
            'date_from' => 'Start Date',
            'date_end' => 'End Date',
            'chart' => [
                'statistic' => 'Statistic',
                'export_image' => 'Export Figure',
                'department' => 'Department',
                'engineer_statistic' => [
                    'candidate_register' => 'Candidiate Engineer Registration',
                    'no_data' => 'There is no candidate registration yet',
                    'yaxis' => 'Number of Student',
                    'btn_candidate_registration' => 'Candidate Registration',
                    'btn_attendence_list' => 'Candidate Attendance List',
                    'student_engineer' => 'Student Engineer Registration',
                    'student_statistic' => 'Student Engineer Statistic',
                    'no_student_registration' => 'There is no student engineer registration',
                    'result_candidate_engineer' => 'Candidate Engineer Result',
                    'result_list' => 'Result List',
                    'result_statistic' => 'Candidate Result',
                    'no_result' => 'The result is not yet released់',
                    'total_pass' => 'Total Pass Students',
                    'pass_female' => 'Total Pass Female Students',
                    'total_reserve' => 'Total Reserve Students',
                    'reserve_female' => 'Total Reserve Female Students',
                    'grade' => 'Grade',
                    'total_student' => 'Total Students',
                    'total_female' => 'Total Female Students',
                ],
                'dut_statistic' => [
                    'candidate_dut_register' => 'Candidate Dut Registration',
                    'no_data' => 'There is no candidate dut registration',
                    'btn_dut_statistic' => 'Candidate Dut Statistic',
                    'btn_register_list' => 'Candidate Dut List',

                    'student_dut_registration_statistic' => 'Student DUT Registration',
                    'student_dut_statistic' => 'Student Dut Statistic',
                    'no_student_registration' => 'There is no student dut registration',

                    'result_candidate_dut' => 'Candidate DUT Result',
                    'dut_result_list' => 'Result List',
                    'dut_result_statistic' => 'Dut Result Statistic',
                    'no_result' => 'The result is not yet released',
                    'male' => 'Male',
                    'female' => 'Female'

                ]
            ]

            //------end vannat
        ],
        'candidates' => [
            'title' => 'Candidates',
            'sub_index_title' => 'All existing candidates',
            'sub_edit_title' => 'Edit student',
            'sub_create_title' => 'Register new candidate',
            'sub_detail_title' => 'Detail of candidate',
            'sub_import_title' => "Import candidates",
            'header' => [
                'personal_information' => 'Personal Information',
                'study_record' => 'Study Record',
                'academic_information' => 'Academic Information'
            ],
            'fields' => [
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
                'math_score' => "Math Score",
                'math_c' => "Math Correct",
                'math_w' => "Math Wrong",
                'math_na' => "Math No Answer",
                'phys_chem_score' => "Physique/Chemistry Score",
                'phys_chem_c' => "Physique/Chemistry Correct",
                'phys_chem_w' => "Physique/Chemistry Wrong",
                'phys_chem_na' => "Physique/Chemistry No Answer",
                'logic_score' => "Logic Score",
                'logic_c' => "Logic Correct",
                'logic_w' => "Logic Wrong",
                'logic_na' => "Logic No Answer",
                'total_s' => "Total Score",
                'average' => "Average",
                'bac_percentile' => "BacII Percentile",
                'active' => "Active",
                'highschool_id' => "High School",
                'promotion_id' => "Promotion",
                'bac_total_score' => "BacII Score",
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
                'payslip_client_id' => "Payment ID",
                'origin_id' => "Origin",
                'preferred_department' => "Prefered Departments",
                'class' => 'Class',
                'room_id' => 'Room',
                'priority_number' => "Number",
                'total_score' => 'Score'
            ]
        ],
        'reporting' => [
            'academic_year_id' => 'Academic Year',
            'degree_id' => 'Degree',
            'department_id' => 'Department',
            'all_department' => 'All Department',
            'is_foreigner' => 'Only Foreigner',
            'date' => "Until",
            'scholarships' => "Scholarships",
            'semester_id' => "Semester"
        ],
        'error' => [
            'reporting' => [
                'title' => 'Reporting System',
                'sub_index_title' => 'List of your suggestion & reporting',
                'sub_create_title' => 'Report error or suggest your demand',
                'sub_edit_title' => 'Edit your request and demand',
                'fields' => [
                    'title' => 'Title',
                    'description' => 'Description',
                    'status' => 'Status',
                    'image' => 'Attachment Image',
                    'created_at' => 'Created at',
                ]
            ],

        ],
        'accounting' => [

            'fields' => [
                'amount_dollar' => "Amount ($)",
                'amount_riel' => "Amount (៛)",
                'amount_kh' => "Amount as Letters",
                'number' => 'Number',
                'account_id' => 'Account Name',
                'payslip_client_id' => 'Income From',
                'date' => 'Date of payment',
                'client' => "Pay to",
                'incomeType_id' => "Income Type",
                'outcomeType_id' => "Outcome Type",
                'description' => "Description",
                'attachment' => "Attachments"
            ]
        ],
        'incomes' => [
            'title' => 'Incomes',
            'sub_index_title' => 'All existing income',
            'sub_edit_title' => 'Edit income information',
            'sub_create_title' => 'Create new income',
            'sub_detail_title' => 'Detail of an income',
            'sub_import_title' => 'Import incomes',
            'fields' => [
                'amount_dollar' => "Amount Dollar",
                'amount_riel' => "Amount Riel",
                'amount_kh' => "In Text",
                'account_id' => "Account",
                'active' => "Active",
                'number' => "Number",
                'client_name' => "Client Name",
                'department' => "Department",
                'income_type' => "Income Type",
                'description' => "Description"
            ]
        ],
        'outcomes' => [
            'title' => 'Outcomes',
            'sub_index_title' => 'All existing outcome',
            'sub_edit_title' => 'Edit outcome information',
            'sub_create_title' => 'Create new outcome',
            'sub_detail_title' => 'Detail of an outcome',
            'search_client' => 'Search for client',
            'search_result' => 'Search result',
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
            'search_result' => 'Search result',
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
            'sub_import_title' => 'Import course',
            'fields' => [
                'course' => 'Cource Name',
                'name' => "Name",
                'semester' => 'Semester',
                'academic_year' => 'Academic year',
                'department' => 'Department',
                'degree' => 'Degree',
                'grade' => 'Grade',
                'class' => 'Class',
                'employee' => 'Lecturer',
                'department_option_id' => 'Option',
                'group' => 'Group',
                'credit' => "Credit",
                'code' => "Code",
                'for_transcript' => "If you checked this option, It means the course you created will be count in calculating score and to include for student's transcript!",
                '10_absece' => "Check this option, if the course require to count the score of student's absences!",
                'allow_resit' => "When you allow student to take supplementary of this course. You must check this option for enabling the inputing score of this subject!"
            ]
        ],

        'Absence' => [
            'title' => 'Student Absence',
            'sub_index_title' => 'View Student Absence',
            'sub_edit_title' => 'Edit Student Absence',
            'sub_create_title' => 'Add Student Absence',
            'sub_detail_title' => 'Detail',
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
        'score' => [
            'title' => 'Score',
            'sub_index_title' => 'View Score',
            "sub_ranking" => "Score Ranking",
            'sub_edit_title' => 'Edit Score',
            'sub_create_title' => 'Add Score',
            'sub_input_title' => 'Input Score',
            'sub_detail_title' => 'Detail',
            'fields' => [
                'name' => "Name",
                'semester' => 'Semester',
                'academic_year_id' => 'Academic year',
                'department_id' => 'Department',
                'degree_id' => 'Degree',
                'grade_id' => 'Grade',
                'employee_id' => 'Lecturer'
            ],
            'clone_score' => [
                'group_info' => ''
            ]
        ],
        'coursePrograms' => [
            'title' => 'Course Programs',
            'sub_index_title' => 'All existing course program',
            'sub_edit_title' => 'Edit course program information',
            'sub_create_title' => 'Create new course program',
            'sub_detail_title' => 'Detail of a course program',
            'sub_import_title' => 'Import course program',
            'fields' => [
                'active' => "Active",
                'name_kh' => "Name in Khmer",
                'name_en' => 'Name in English',
                'name_fr' => 'Name in French',
                'duration' => 'Duration',
                'code' => 'Code',
                'credit' => 'Credit',
                'time_tp' => "TP",
                'time_td' => 'TD',
                'time_course' => 'Course',
                'degree' => "Degree",
                'grade' => "Grade",
                'department' => "Department",
                'department_option' => "Option",
                'semester' => "Semester",
                'responsible_department_id' => "Permit responsibility to",
                'reference_course_id' => 'Reference Course',
                'reference_course_id_title' => 'This is a course program from SA/SF. This action will allow you to copy the score to the course you are creating!'
            ]
        ],
        'scholarships' => [
            'title' => 'Scholarships',
            'sub_index_title' => 'All existing scholarships',
            'sub_edit_title' => 'Edit scholarship information',
            'sub_create_title' => 'Create new scholarship',
            'sub_detail_title' => 'Detail of a scholarship',
            'sub_import_title' => 'Import scholarships',
            'import_holder' => 'Import Scholarship Holders',
            'general_information' => 'General Information',
            'more_information' => 'More Information',
            'school_fee_and_award' => 'School Fee and Award',
            'scholarship_holder' => 'Scholarship Holder',
            'fields' => [
                'name' => "Name",
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
                'school_fee_rate' => 'School Fee Rate',
                'scholarship_budget' => 'Scholarship Budget',
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
        'promotions' => [
            'title' => 'Promotions',
            'sub_index_title' => 'All existing promotions',
            'sub_edit_title' => 'Edit promotion information',
            'sub_create_title' => 'Create new promotion',
            'sub_detail_title' => 'Detail of a promotion',
            'fields' => [
                'name' => "Name",
                'observation' => 'Observation',
                'active' => 'Active',
            ]
        ],
        'departmentOptions' => [
            'title' => 'Department Option / Technique',
            'sub_index_title' => 'All existing department option',
            'sub_edit_title' => 'Edit department option information',
            'sub_create_title' => 'Create new department option',
            'sub_detail_title' => 'Detail of a department option',
            'fields' => [
                'name_kh' => "Name Khmer",
                'name_en' => 'Name English',
                'name_fr' => 'Name French',
                'active' => 'Active',
                'code' => 'Code',
                'department_id' => 'Department',
                'degree_id' => 'Degree'
            ]
        ],

        'studentPayments' => [
            'title' => 'Student Payment',
            'sub_index_title' => 'All existing student payments',
            'sub_edit_title' => 'Edit student payment information',
            'sub_create_title' => 'Create new payment',
            'sub_detail_title' => 'Detail of a payment',
            'fields' => [
                'name' => "Name",
                'observation' => 'Observation',
                'active' => 'Active',
            ]
        ],
        'candidatePayments' => [
            'title' => 'Candidate Payment',
            'sub_index_title' => 'All existing candidate payments',
            'sub_edit_title' => 'Edit candidate payment information',
            'sub_create_title' => 'Create new payment',
            'sub_detail_title' => 'Detail of a payment',
        ],

        'courseAnnual' => [
            'title' => 'Courses Annually',
            'lecturer' => 'Lecturers',
            'course_assignment' => 'Course Assignment'
        ],
        'configurations' => [
            'title' => 'Configurations',
            'sub_index_title' => 'All available configurations',
            'sub_create_title' => 'Create configurations',
            'sub_edit_title' => 'Edit configurations',
            'sub_detail_title' => 'Detail of configurations',
            'fields' => [
                'key' => 'Key',
                'value' => 'Value',
                'description' => "Description"
            ]
        ],

        'schedule' => [
            'calendar' => [
                'title' => 'Calendar Management',
                'sub_title' => 'Sub title calendar management',
                'panel' => [
                    'title' => 'Calendar'
                ]
            ],
            'event' => [
                'panel' => [
                    'title' => 'Event'
                ],
                'modal_create_event' => [
                    'title' => 'Add a new event',
                    'form_input' => [
                        'title' => 'Title',
                        'department' => 'Departments',
                        'event_type' => 'Type',
                        'start_date' => 'Start Date',
                        'end_date' => 'End Date',
                        'allow_student_study' => 'Allow student study',
                        'repeat_daily_year' => 'Daily Year',
                        'placeholder' => [
                            'title' => 'Event title',
                            'chose_department' => 'Chose Department',
                            'start_date' => 'Start Date',
                            'end_date' => 'End Date'
                        ]
                    ]
                ]
            ],
            'timetable' => [
                'meta_title' => 'Timetable Management',
                'title' => 'Schedule Management',
                'sub_index_title' => 'Timetable Management',
                'modal_clone' => [
                    'title' => 'Clone Timetable',
                    'body' => [
                        'all_weeks' => 'All Weeks',
                        'all_groups' => 'All Groups'
                    ]
                ],
                'modal_reset' => [
                    'title' => 'Reset Timetable'
                ],
                'courses_sessions' => 'Courses Sessions',
                'rooms' => 'Rooms',
                'table' => [
                    'academic_year' => 'Academic Year',
                    'department' => 'Department',
                    'degree' => 'Degree',
                    'grade' => 'Grade',
                    'option' => 'Option',
                    'group' => 'Group',
                    'semester' => 'Semester',
                    'week' => 'Week',
                    'status' => 'Status'
                ],
                'status' => [
                    'finished' => 'FINISHED',
                    'in_progress' => 'IN PROGRESS',
                    'waiting' => 'WAITING'
                ],
                'popup_export' => [
                    'box_title' => 'Export Timetable'
                ],
                'popup_print' => [
                    'box_title' => 'Print Timetable'
                ],
                'popup_print_template' => [
                    'title' => 'EMPLOI DU TEMPS',
                    'group' => 'Groupe',
                    'semester' => 'Semestre',
                    'week' => 'Semaine',
                    'days' => [
                        'hours' => 'Horaire',
                        'monday' => 'Lundi',
                        'tuesday' => 'Mardi',
                        'wednesday' => 'Mercredi',
                        'thursday' => 'Jeudi',
                        'friday' => 'Vendredi',
                        'saturday' => 'Samedi'
                    ]
                ]
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
        ]
    ],
];
