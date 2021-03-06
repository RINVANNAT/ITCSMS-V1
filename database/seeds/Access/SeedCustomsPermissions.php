<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SeedCustomsPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * php artisan db:seed --class=SeedCustomsPermissions
     */
    public function run()
    {
        /* Access Permissions */
        $roots[] = [
            'name' => 'Access',
            'groups' => [
                [
                    'name' => 'User',
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name' => 'create-users',
                            'display_name' => 'Create Users',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'edit-users',
                            'display_name' => 'Edit Users',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'delete-users',
                            'display_name' => 'Delete Users',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'change-user-password',
                            'display_name' => 'Change User Password',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'deactivate-users',
                            'display_name' => 'Deactivate Users',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'reactivate-users',
                            'display_name' => 'Re-Activate Users',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'undelete-users',
                            'display_name' => 'Restore Users',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'permanently-delete-users',
                            'display_name' => 'Permanently Delete Users',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'resend-user-confirmation-email',
                            'display_name' => 'Resend Confirmation E-mail',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                    ]
                ],
                [
                    'name' => 'Role',
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name' => 'create-roles',
                            'display_name' => 'Create Roles',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'edit-roles',
                            'display_name' => 'Edit Roles',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'delete-roles',
                            'display_name' => 'Delete Roles',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Permission',
                    'groups' => [

                    ],
                    'permissions' => [
                        /* Permissions Group */
                        [
                            'name' => 'create-permission-groups',
                            'display_name' => 'Create Permission Groups',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'edit-permission-groups',
                            'display_name' => 'Edit Permission Groups',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'delete-permission-groups',
                            'display_name' => 'Delete Permission Groups',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'sort-permission-groups',
                            'display_name' => 'Sort Permission Groups',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        /* Permissions */
                        [
                            'name' => 'create-permissions',
                            'display_name' => 'Create Permissions',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'edit-permissions',
                            'display_name' => 'Edit Permissions',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ],
                        [
                            'name' => 'delete-permissions',
                            'display_name' => 'Delete Permissions',
                            'dependency' => [
                                'view-backend', 'view-access-management'
                            ]
                        ]
                    ]
                ]
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name' => 'view-backend',
                    'display_name' => 'View Backend',
                    'dependency' => [
                    ]
                ],
                [
                    'name' => 'view-access-management',
                    'display_name' => 'View Access Management',
                    'dependency' => [
                    ]
                ]
            ]
        ];

        /* Student affair permissions */
        $roots[] = [
            'name' => 'Student & Study Affair',
            'groups' => [
                [
                    'name' => 'Student',
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name' => 'view-student-management',
                            'display_name' => 'View Student Management',
                            'dependency' => [
                                'view-backend'
                            ]
                        ],
                        [
                            'name' => 'create-students',
                            'display_name' => 'Create Students',
                            'dependency' => [
                                'view-backend', 'view-student-management'
                            ]
                        ],
                        [
                            'name' => 'edit-students',
                            'display_name' => 'Edit Students',
                            'dependency' => [
                                'view-backend', 'view-student-management'
                            ]
                        ],
                        [
                            'name' => 'delete-students',
                            'display_name' => 'Delete Students',
                            'dependency' => [
                                'view-backend', 'view-student-management'
                            ]
                        ],
                        [
                            'name' => 'generate-student-group',
                            'display_name' => 'Generate Student Group',
                            'dependency' => [
                                'view-backend', 'view-student-management'
                            ]
                        ],
                        [
                            'name' => 'generate-student-id-card',
                            'display_name' => 'Generate Student ID Cards',
                            'dependency' => [
                                'view-backend', 'view-student-management'
                            ]
                        ],
                        [
                            'name' => 'print-students-id-card',
                            'display_name' => 'Print Student ID Cards',
                            'dependency' => [
                                'view-backend', 'view-student-management'
                            ]
                        ],
                        [
                            'name' => 'print-transcript',
                            'display_name' => 'Print Transcript',
                            'dependency' => [
                                'view-backend', 'view-student-management'
                            ]
                        ],
                        [
                            'name' => 'manage-student-reporting',
                            'display_name' => 'Manage student reporting',
                            'dependency' => [
                                'view-backend', 'view-student-management'
                            ]
                        ],
                        [
                            'name' => 'export-student-list',
                            'display_name' => 'Export student list',
                            'dependency' => [
                                'view-backend', 'view-student-management'
                            ]
                        ]
                    ]
                ]

            ],
            'permissions' => [
                // Leave it empty if there is none

            ]
        ];

        /* Accounting Permissions */
        $roots[] = [
            'name' => 'Accounting',
            'groups' => [
                [
                    'name' => 'Income',
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name' => 'view-income-management',
                            'display_name' => 'View Income Management',
                            'dependency' => [
                                'view-backend', 'view-accounting-management'
                            ]
                        ],
                        [
                            'name' => 'create-incomes',
                            'display_name' => 'Create Incomes',
                            'dependency' => [
                                'view-backend', 'view-accounting-management', 'view-income-management'
                            ]
                        ],
                        [
                            'name' => 'edit-incomes',
                            'display_name' => 'Edit Incomes',
                            'dependency' => [
                                'view-backend', 'view-accounting-management', 'view-income-management'
                            ]
                        ],
                        [
                            'name' => 'delete-incomes',
                            'display_name' => 'Delete Incomes',
                            'dependency' => [
                                'view-backend', 'view-accounting-management', 'view-income-management'
                            ]
                        ],
                    ]
                ],
                [
                    'name' => 'Outcome',
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name' => 'view-outcome-management',
                            'display_name' => 'View Outcome Management',
                            'dependency' => [
                                'view-backend', 'view-accounting-management'
                            ]
                        ],
                        [
                            'name' => 'create-outcomes',
                            'display_name' => 'Create Outcomes',
                            'dependency' => [
                                'view-backend', 'view-accounting-management', 'view-outcome-management'
                            ]
                        ],
                        [
                            'name' => 'edit-outcomes',
                            'display_name' => 'Edit Outcomes',
                            'dependency' => [
                                'view-backend', 'view-accounting-management', 'view-outcome-management'
                            ]
                        ],
                        [
                            'name' => 'delete-outcomes',
                            'display_name' => 'Edit Outcomes',
                            'dependency' => [
                                'view-backend', 'view-accounting-management', 'view-outcome-management'
                            ]
                        ],
                    ]
                ],
                [
                    'name' => 'Student Payment',
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name' => 'view-student-payment-management',
                            'display_name' => 'View Student Payment Management',
                            'dependency' => [
                                'view-backend', 'view-accounting-management'
                            ]
                        ]

                    ]
                ],
                [
                    'name' => 'Customer',
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name' => 'view-customer-management',
                            'display_name' => 'View Customer Management',
                            'dependency' => [
                                'view-backend', 'view-accounting-management'
                            ]
                        ],
                        [
                            'name' => 'create-customers',
                            'display_name' => 'Create Customers',
                            'dependency' => [
                                'view-backend', 'view-accounting-management', 'view-customer-management'
                            ]
                        ],
                        [
                            'name' => 'edit-customers',
                            'display_name' => 'Edit Customers',
                            'dependency' => [
                                'view-backend', 'view-accounting-management', 'view-customer-management'
                            ]
                        ],
                        [
                            'name' => 'delete-customers',
                            'display_name' => 'Delete Customers',
                            'dependency' => [
                                'view-backend', 'view-accounting-management', 'view-customer-management'
                            ]
                        ]
                    ]
                ]
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name' => 'view-accounting-management',
                    'display_name' => 'View Accounting Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ]
            ]
        ];

        /* Scholarship Permissions */
        $roots[] = [
            'name' => 'Scholarship',
            'groups' => [
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name' => 'view-scholarship-management',
                    'display_name' => 'View Scholarship Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ],
                [
                    'name' => 'create-scholarships',
                    'display_name' => 'Create Scholarships',
                    'dependency' => [
                        'view-backend', 'view-scholarship-management'
                    ]
                ],
                [
                    'name' => 'edit-scholarships',
                    'display_name' => 'Edit Scholarships',
                    'dependency' => [
                        'view-backend', 'view-scholarship-management'
                    ]
                ],
                [
                    'name' => 'delete-scholarships',
                    'display_name' => 'Delete Scholarships',
                    'dependency' => [
                        'view-backend', 'view-scholarship-management'
                    ]
                ]
            ]
        ];

        /* Examination Permissions */
        $roots[] = [
            'name' => 'Examination',
            'groups' => [
                [
                    'name' => 'Examination Documents',
                    'groups' => [
                    ],
                    'permissions' => [
                        [
                            'name' => 'view-exam-document',
                            'display_name' => "View exam's documents",
                            'dependency' => [
                                'view-backend', 'view-exam-management'
                            ]
                        ],
                        [
                            'name' => 'download-dut-result-detail',
                            'display_name' => "Download DUT Result Detail",
                            'dependency' => [
                                'view-backend', 'view-exam-management'
                            ]
                        ],
                        [
                            'name' => 'download-ing-result-detail',
                            'display_name' => "Download Engineer Result Detail",
                            'dependency' => [
                                'view-backend', 'view-exam-management'
                            ]
                        ],
                        [
                            'name' => 'download-examination-document',
                            'display_name' => "Download examination's documents",
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-document'
                            ]
                        ],
                    ]
                ],
                [
                    'name' => 'Staff',
                    'groups' => [
                    ],
                    'permissions' => [
                        [
                            'name' => 'view-exam-staff',
                            'display_name' => "View exam's staffs",
                            'dependency' => [
                                'view-backend', 'view-exam-management'
                            ]
                        ],
                        [
                            'name' => 'modify-examination-staff',
                            'display_name' => 'Modify Examination Staff',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-staff'
                            ]
                        ],
                        [
                            'name' => 'add-temporary-examination-staff',
                            'display_name' => 'Add/Import Examination Staff from Ministry',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-staff'
                            ]
                        ],
                    ]
                ],
                [
                    'name' => 'Examination Course',
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name' => 'view-entrance-exam-course',
                            'display_name' => "View entrance exam's courses",
                            'dependency' => [
                                'view-backend', 'view-exam-management'
                            ]
                        ],
                        [
                            'name' => 'create-entrance-exam-course',
                            'display_name' => 'Add new entrance exam course',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-entrance-exam-course'
                            ]

                        ],
                        [
                            'name' => 'edit-entrance-exam-course',
                            'display_name' => 'Edit entrance exam course',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-entrance-exam-course'
                            ]

                        ],
                        [
                            'name' => 'delete-entrance-exam-course',
                            'display_name' => 'Delete entrance exam course',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-entrance-exam-course'
                            ]

                        ],
                        [
                            'name' => 'create-entrance-exam-score',
                            'display_name' => 'Input Entrance Exam Score',
                            'dependency' => [
                                'view-backend', 'view-exam-management'
                            ]

                        ],
                        [
                            'name' => 'get-candidate-result-score',
                            'display_name' => 'Generate Candidate Score (Engineer)',
                            'dependency' => [
                                'view-backend', 'view-exam-management'
                            ]

                        ],
                        [
                            'name' => 'get-candidate-result-score-dut',
                            'display_name' => 'Generate Candidate Score (DUT)',
                            'dependency' => [
                                'view-backend', 'view-exam-management'
                            ]

                        ],
                        [
                            'name' => 'report-error-on-inputted-score',
                            'display_name' => 'Get Inputted Score Error && Add New Correction',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'create-entrance-exam-score'
                            ]
                        ],
                        [
                            'name' => 'view-entrance-exam-course-score',
                            'display_name' => 'Get Inputted Score Error && Add New Correction',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'create-entrance-exam-score'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Examination Candidate',
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name' => 'view-exam-candidate',
                            'display_name' => "View exam's candidate",
                            'dependency' => [
                                'view-backend', 'view-exam-management'
                            ]
                        ],
                        [
                            'name' => 'create-exam-candidate',
                            'display_name' => 'Add new exam candidate',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-candidate'
                            ]

                        ],
                        [
                            'name' => 'edit-exam-candidate',
                            'display_name' => 'Edit exam candidate',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-candidate'
                            ]

                        ],
                        [
                            'name' => 'delete-exam-candidate',
                            'display_name' => 'Delete exam candidate',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-candidate'
                            ]

                        ],
                        [
                            'name' => 'register-exam-candidate',
                            'display_name' => 'Register candidate',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-candidate'
                            ]

                        ],
                        [
                            'name' => 'generate-room-exam-candidate',
                            'display_name' => 'Generate examination room',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-candidate'
                            ]

                        ],
                        [
                            'name' => 'view-exam-candidate-score',
                            'display_name' => "View candidate's score",
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-candidate'
                            ]

                        ],
                    ]
                ],
                [
                    'name' => 'Examination Room',
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name' => 'view-exam-room',
                            'display_name' => "View exam's room",
                            'dependency' => [
                                'view-backend', 'view-exam-management'
                            ]
                        ],
                        [
                            'name' => 'modify-exam-room',
                            'display_name' => 'Modify exam room',
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-room'
                            ]
                        ],
                        [
                            'name' => 'view-secret-codes',
                            'display_name' => "View exam's rooms secret code",
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-room'
                            ]
                        ],
                        [
                            'name' => 'generate-exam-room-secret-code',
                            'display_name' => "Generate exam's rooms secret code",
                            'dependency' => [
                                'view-backend', 'view-exam-management', 'view-exam-room', 'view-secret-codes'
                            ]
                        ],
                    ]
                ]
            ],
            'permissions' => [
                // Leave it empty if there is none

                [
                    'name' => 'view-exam-management',
                    'display_name' => 'View Exam Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ],
                [
                    'name' => 'create-exams',
                    'display_name' => 'Create Exams',
                    'dependency' => [
                        'view-backend', 'view-exam-management'
                    ]
                ],
                [
                    'name' => 'edit-exams',
                    'display_name' => 'Edit Exams',
                    'dependency' => [
                        'view-backend', 'view-exam-management'
                    ]
                ],
                [
                    'name' => 'delete-exams',
                    'display_name' => 'Delete Exams',
                    'dependency' => [
                        'view-backend', 'view-exam-management'
                    ]
                ]
            ]
        ];

        /* Course Permissions */
        $roots[] = [
            'name' => 'Course',
            'groups' => [
                [
                    'name' => 'Course Annual',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-courseAnnual-management',
                            'display_name' => 'View Course Annual Management',
                            'dependency' => [
                                'view-backend', 'view-course-management'
                            ]
                        ],
                        [
                            'name' => 'create-courseAnnuals',
                            'display_name' => 'Create Course Annuals',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'delete-courseAnnuals',
                            'display_name' => 'Delete Course Annuals',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'edit-courseAnnuals',
                            'display_name' => 'Edit Course Annuals',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'import-courseAnnuals',
                            'display_name' => 'Import Course Annuals',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'course-annual-assignment',
                            'display_name' => 'Assign Course Annual for Each Teacher',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'generate-course-annual',
                            'display_name' => 'Generate Course Annual From The Previous Course AnnualProgram',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'view-all-score-course-annual', // View all score/course in a department
                            'display_name' => 'View all scores of course annual',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'view-all-score-in-all-department', // View all score/course in all departments
                            'display_name' => 'View all score in all department',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'input-score-course-annual',
                            'display_name' => 'Input each score course annual',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'disable-enable-input-score-into-course-annual',
                            'display_name' => 'Disable/Enable scoring in own department',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'disable-enable-input-score-into-course-annual-in-all-department',
                            'display_name' => 'Disable/Enable scoring in all department',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'input-score-without-blocking',
                            'display_name' => 'Input score without blocking (Special Permission - Be careful)',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'evaluate-student',
                            'display_name' => 'To evaluate student for the final result,the user is able to set student status as Radie or Redouble',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ],
                        [
                            'name' => 'write-student-remark',
                            'display_name' => 'A permission to make a note on each student remark',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseAnnual-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Course Program',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-courseProgram-management',
                            'display_name' => 'View Course Program Management',
                            'dependency' => [
                                'view-backend', 'view-course-management'
                            ]
                        ],
                        [
                            'name' => 'create-coursePrograms',
                            'display_name' => 'Create Course Programs',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseProgram-management'
                            ]
                        ],
                        [
                            'name' => 'delete-coursePrograms',
                            'display_name' => 'Delete Course Programs',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseProgram-management'
                            ]
                        ],
                        [
                            'name' => 'edit-coursePrograms',
                            'display_name' => 'Edit Course Programs',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseProgram-management'
                            ]
                        ],
                        [
                            'name' => 'import-coursePrograms',
                            'display_name' => 'Import Course Programs',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseProgram-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Course Session',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-courseSession-management',
                            'display_name' => 'View Course Session Management',
                            'dependency' => [
                                'view-backend', 'view-course-management'
                            ]
                        ],
                        [
                            'name' => 'create-courseSessions',
                            'display_name' => 'Create Course Sessions',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseSession-management'
                            ]
                        ],
                        [
                            'name' => 'delete-courseSessions',
                            'display_name' => 'Delete Course Sessions',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseSession-management'
                            ]
                        ],
                        [
                            'name' => 'edit-courseSessions',
                            'display_name' => 'Edit Course Sessions',
                            'dependency' => [
                                'view-backend', 'view-course-management', 'view-courseSession-management'
                            ]
                        ]
                    ]
                ],
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name' => 'view-course-management',
                    'display_name' => 'View Course Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ]
            ]
        ];

        $roots[] = [
            'name' => 'Score',
            'groups' => [
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name' => 'view-score-management',
                    'display_name' => 'View Score Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ],
                [
                    'name' => 'create-score',
                    'display_name' => 'Create Score',
                    'dependency' => [
                        'view-backend', 'view-score-management'
                    ]
                ],
                [
                    'name' => 'edit-score',
                    'display_name' => 'Edit Score',
                    'dependency' => [
                        'view-backend', 'view-score-management'
                    ]
                ],
                [
                    'name' => 'delete-score',
                    'display_name' => 'Delete Score',
                    'dependency' => [
                        'view-backend', 'view-score-management'
                    ]
                ],
                [
                    'name' => 'import-score',
                    'display_name' => 'Import Score',
                    'dependency' => [
                        'view-backend', 'view-score-management'
                    ]
                ]
            ]
        ];

        $roots[] = [
            'name' => 'Absence',
            'groups' => [
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name' => 'view-absence-management',
                    'display_name' => 'View Absence Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ],
                [
                    'name' => 'create-absence',
                    'display_name' => 'Create Absence',
                    'dependency' => [
                        'view-backend', 'view-absence-management'
                    ]
                ],
                [
                    'name' => 'edit-absence',
                    'display_name' => 'Edit Absence',
                    'dependency' => [
                        'view-backend', 'view-absence-management'
                    ]
                ],
                [
                    'name' => 'delete-absence',
                    'display_name' => 'Delete Absence',
                    'dependency' => [
                        'view-backend', 'view-absence-management'
                    ]
                ],
                [
                    'name' => 'import-absence',
                    'display_name' => 'Import Absence',
                    'dependency' => [
                        'view-backend', 'view-absence-management'
                    ]
                ]
            ]
        ];

        $roots[] = [
            'name' => 'EvalStatus',
            'groups' => [
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name' => 'view-evalStatus-management',
                    'display_name' => 'View Eval Status Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ],
                [
                    'name' => 'create-evalStatus',
                    'display_name' => 'Create Eval Status',
                    'dependency' => [
                        'view-evalStatus-management'
                    ]
                ],
                [
                    'name' => 'edit-evalStatus',
                    'display_name' => 'Edit Eval Status',
                    'dependency' => [
                        'view-backend', 'view-evalStatus-management'
                    ]
                ],
                [
                    'name' => 'delete-evalStatus',
                    'display_name' => 'Delete Eval Status',
                    'dependency' => [
                        'view-backend', 'view-evalStatus-management'
                    ]
                ],
                [
                    'name' => 'import-evalStatus',
                    'display_name' => 'Import Eval Status',
                    'dependency' => [
                        'view-backend', 'view-evalStatus-management'
                    ]
                ]
            ]
        ];

        /* Employee Permissions */
        $roots[] = [
            'name' => 'Employee',
            'groups' => [
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name' => 'view-employee-management',
                    'display_name' => 'View Employee Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ],
                [
                    'name' => 'create-employees',
                    'display_name' => 'Create Employees',
                    'dependency' => [
                        'view-backend', 'view-employee-management'
                    ]
                ],
                [
                    'name' => 'edit-employees',
                    'display_name' => 'Edit Employees',
                    'dependency' => [
                        'view-backend', 'view-employee-management'
                    ]
                ],
                [
                    'name' => 'delete-employees',
                    'display_name' => 'Delete Employees',
                    'dependency' => [
                        'view-backend', 'view-employee-management'
                    ]
                ],
                [
                    'name' => 'view-all-employees',
                    'display_name' => 'View All Employees',
                    'dependency' => [
                        'view-backend', 'view-employee-management'
                    ]
                ]
            ]
        ];

        /* Inventory Permissions */
        $roots[] = [
            'name' => 'Inventory',
            'groups' => [
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name' => 'view-inventory-management',
                    'display_name' => 'View Inventory Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ],
                [
                    'name' => 'create-inventories',
                    'display_name' => 'Create Inventories',
                    'dependency' => [
                        'view-backend', 'view-inventory-management'
                    ]
                ],
                [
                    'name' => 'edit-inventories',
                    'display_name' => 'Edit Inventories',
                    'dependency' => [
                        'view-backend', 'view-inventory-management'
                    ]
                ],
                [
                    'name' => 'delete-inventories',
                    'display_name' => 'Delete Inventories',
                    'dependency' => [
                        'view-backend', 'view-inventory-management'
                    ]
                ]
            ]
        ];

        /* Configuration Permissions */
        $roots[] = [
            'name' => 'Configuration',
            'groups' => [
                [
                    'name' => 'Department',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-department-management',
                            'display_name' => 'View Department Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-departments',
                            'display_name' => 'Create Departments',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-department-management'
                            ]
                        ],
                        [
                            'name' => 'edit-departments',
                            'display_name' => 'Edit Departments',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-department-management'
                            ]
                        ],
                        [
                            'name' => 'delete-departments',
                            'display_name' => 'Delete Departments',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-department-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Degree',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-degree-management',
                            'display_name' => 'View Degree Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-degrees',
                            'display_name' => 'Create Degrees',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-degree-management'
                            ]
                        ],
                        [
                            'name' => 'edit-degrees',
                            'display_name' => 'Edit Degrees',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-degree-management'
                            ]
                        ],
                        [
                            'name' => 'delete-degrees',
                            'display_name' => 'Delete Degrees',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-degree-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Grade',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-grade-management',
                            'display_name' => 'View Grade Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-grades',
                            'display_name' => 'Create Grades',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-grade-management'
                            ]
                        ],
                        [
                            'name' => 'edit-grades',
                            'display_name' => 'Edit Grades',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-grade-management'
                            ]
                        ],
                        [
                            'name' => 'delete-grades',
                            'display_name' => 'Delete Grades',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-grade-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Academic Year',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-academicYear-management',
                            'display_name' => 'View Academic Year Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-academicYears',
                            'display_name' => 'Create Academic Years',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-academicYear-management'
                            ]
                        ],
                        [
                            'name' => 'edit-academicYears',
                            'display_name' => 'Edit Academic Years',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-academicYear-management'
                            ]
                        ],
                        [
                            'name' => 'delete-academicYears',
                            'display_name' => 'Delete Academic Years',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-academicYear-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Account',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-account-management',
                            'display_name' => 'View Account Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-accounts',
                            'display_name' => 'Create Accounts',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-account-management'
                            ]
                        ],
                        [
                            'name' => 'edit-accounts',
                            'display_name' => 'Edit Accounts',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-account-management'
                            ]
                        ],
                        [
                            'name' => 'delete-accounts',
                            'display_name' => 'Delete Accounts',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-account-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Building',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-building-management',
                            'display_name' => 'View Building Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-buildings',
                            'display_name' => 'Create Buildings',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-building-management'
                            ]
                        ],
                        [
                            'name' => 'edit-buildings',
                            'display_name' => 'Edit Buildings',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-building-management'
                            ]
                        ],
                        [
                            'name' => 'delete-buildings',
                            'display_name' => 'Delete Buildings',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-building-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'High School',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-highSchool-management',
                            'display_name' => 'View High School Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-highSchools',
                            'display_name' => 'Create High Schools',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-highSchool-management'
                            ]
                        ],
                        [
                            'name' => 'edit-highSchools',
                            'display_name' => 'Edit High Schools',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-highSchool-management'
                            ]
                        ],
                        [
                            'name' => 'delete-highSchools',
                            'display_name' => 'Delete High Schools',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-highSchool-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Income Type',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-incomeType-management',
                            'display_name' => 'View Income Type Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-incomeTypes',
                            'display_name' => 'Create Income Types',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-incomeType-management'
                            ]
                        ],
                        [
                            'name' => 'edit-incomeTypes',
                            'display_name' => 'Edit Income Types',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-incomeType-management'
                            ]
                        ],
                        [
                            'name' => 'delete-incomeTypes',
                            'display_name' => 'Delete Income Types',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-incomeType-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Outcome Type',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-outcomeType-management',
                            'display_name' => 'View Outcome Type Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-outcomeTypes',
                            'display_name' => 'Create Outcome Types',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-outcomeType-management'
                            ]
                        ],
                        [
                            'name' => 'edit-outcomeTypes',
                            'display_name' => 'Edit Outcome Types',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-outcomeType-management'
                            ]
                        ],
                        [
                            'name' => 'delete-outcomeTypes',
                            'display_name' => 'Delete Outcome Types',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-outcomeType-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'School & Scholarship Fee',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-schoolFee-management',
                            'display_name' => 'View School Fee Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-schoolFees',
                            'display_name' => 'Create School Fees',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-schoolFee-management'
                            ]
                        ],
                        [
                            'name' => 'edit-schoolFees',
                            'display_name' => 'Edit School Fees',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-schoolFee-management'
                            ]
                        ],
                        [
                            'name' => 'delete-schoolFees',
                            'display_name' => 'Delete School Fees',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-schoolFee-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Room',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-room-management',
                            'display_name' => 'View Room Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-rooms',
                            'display_name' => 'Create Rooms',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-room-management'
                            ]
                        ],
                        [
                            'name' => 'edit-rooms',
                            'display_name' => 'Edit Rooms',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-room-management'
                            ]
                        ],
                        [
                            'name' => 'delete-rooms',
                            'display_name' => 'Delete Rooms',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-room-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Room Type',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-roomType-management',
                            'display_name' => 'View Room Type Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-roomTypes',
                            'display_name' => 'Create Room Types',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-roomType-management'
                            ]
                        ],
                        [
                            'name' => 'edit-roomTypes',
                            'display_name' => 'Edit Room Types',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-roomType-management'
                            ]
                        ],
                        [
                            'name' => 'delete-roomTypes',
                            'display_name' => 'Delete Room Types',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-roomType-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Student BacII',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-studentBac2-management',
                            'display_name' => 'View Student BacII Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-studentBac2s',
                            'display_name' => 'Create Student BacII',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-studentBac2-management'
                            ]
                        ],
                        [
                            'name' => 'edit-studentBac2s',
                            'display_name' => 'Edit Student BacII',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-studentBac2-management'
                            ]
                        ],
                        [
                            'name' => 'delete-studentBac2s',
                            'display_name' => 'Delete Student BacII',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-studentBac2-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Department Option/ Technique',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-departmentOption-management',
                            'display_name' => 'View Department Option Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-departmentOptions',
                            'display_name' => 'Create Department Options',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-departmentOption-management'
                            ]
                        ],
                        [
                            'name' => 'edit-departmentOptions',
                            'display_name' => 'Edit Department Options',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-departmentOption-management'
                            ]
                        ],
                        [
                            'name' => 'delete-departmentOptions',
                            'display_name' => 'Delete Department Options',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-departmentOption-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Promotion',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-promotion-management',
                            'display_name' => 'View Promotion Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-promotions',
                            'display_name' => 'Create Promotions',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-promotion-management'
                            ]
                        ],
                        [
                            'name' => 'edit-promotions',
                            'display_name' => 'Edit Promotions',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-promotion-management'
                            ]
                        ],
                        [
                            'name' => 'delete-promotions',
                            'display_name' => 'Delete Promotions',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-promotion-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Redouble',
                    'groups' => [
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name' => 'view-redouble-management',
                            'display_name' => 'View Redouble Management',
                            'dependency' => [
                                'view-backend', 'view-configuration-management'
                            ]
                        ],
                        [
                            'name' => 'create-redoubles',
                            'display_name' => 'Create Redoubles',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-redouble-management'
                            ]
                        ],
                        [
                            'name' => 'edit-redoubles',
                            'display_name' => 'Edit Redoubles',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-redouble-management'
                            ]
                        ],
                        [
                            'name' => 'delete-redoubles',
                            'display_name' => 'Delete Redoubles',
                            'dependency' => [
                                'view-backend', 'view-configuration-management', 'view-redouble-management'
                            ]
                        ]
                    ]
                ],

            ],
            'permissions' => [
                // Permissions for configuration
                // Leave it empty if there is none
                [
                    'name' => 'view-configuration-management',
                    'display_name' => 'View Configuration Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ]
            ]
        ];

        /* Inventory Permissions */
        $roots[] = [
            'name' => 'Error Reporting',
            'groups' => [
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name' => 'view-reporting-management',
                    'display_name' => 'View Error Reporting Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ],
                [
                    'name' => 'create-reporting',
                    'display_name' => 'Create error reporting',
                    'dependency' => [
                        'view-backend', 'view-reporting-management'
                    ]
                ],
                [
                    'name' => 'edit-reporting',
                    'display_name' => 'Edit error reporting',
                    'dependency' => [
                        'view-backend', 'view-reporting-management'
                    ]
                ],
                [
                    'name' => 'delete-reporting',
                    'display_name' => 'Delete error reporting',
                    'dependency' => [
                        'view-backend', 'view-reporting-management'
                    ]
                ]
            ]
        ];

        $roots[] = [
            'name' => 'Other',
            'groups' => [

            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name' => 'view-log-viewer-management',
                    'display_name' => 'View Log Viewer',
                    'dependency' => [
                        'view-backend'
                    ]
                ]
            ]
        ];

        $roots[] = [
            'name' => 'View Internship',
            'groups' => [],
            'permissions' => [
                [
                    'name' => 'view-internship-management',
                    'display_name' => 'View internship Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ],
                [
                    'name' => 'create-internship',
                    'display_name' => 'Create an internship',
                    'dependency' => [
                        'view-backend', 'view-internship-management'
                    ]
                ],
                [
                    'name' => 'edit-internship',
                    'display_name' => 'Edit an internship',
                    'dependency' => [
                        'view-backend', 'view-internship-management'
                    ]
                ],
                [
                    'name' => 'delete-internship',
                    'display_name' => 'Delete an internship',
                    'dependency' => [
                        'view-backend', 'view-internship-management'
                    ]
                ]
            ]
        ];

        /* Schedule Permission */
        $roots[] = [
            'name' => 'Schedule Management',
            'groups' => [
                [
                    'name' => 'Calendar Management',
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name' => 'view-calendar-management',
                            'display_name' => 'View Calendar Viewer',
                            'dependency' => [
                                'view-backend', 'view-schedule-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Timetable Management',
                    'groups' => [],
                    'permissions' => [
                        [
                            'name' => 'view-timetable-management',
                            'display_name' => 'View Timetable Viewer',
                            'dependency' => [
                                'view-backend', 'view-schedule-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Event Management',
                    'groups' => [],
                    'permissions' => [
                        [
                            'name' => 'view-event',
                            'display_name' => 'View Event',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-calendar-management'
                            ]
                        ],
                        [
                            'name' => 'create-event',
                            'display_name' => 'Create Event',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-calendar-management'
                            ]
                        ],
                        [
                            'name' => 'edit-event',
                            'display_name' => 'Edit Event',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-calendar-management'
                            ]
                        ],
                        [
                            'name' => 'delete-event',
                            'display_name' => 'Delete Event',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-calendar-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Create Event',
                    'groups' => [],
                    'permissions' => [
                        [
                            'name' => 'create-public-event',
                            'display_name' => 'Create Public Event',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-calendar-management', 'create-event'
                            ]
                        ],
                        [
                            'name' => 'create-private-event',
                            'display_name' => 'Create Private Event',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-calendar-management', 'create-event'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Timetable Management',
                    'groups' => [],
                    'permissions' => [
                        [
                            'name' => 'view-timetable',
                            'display_name' => 'View Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ],
                        [
                            'name' => 'create-timetable',
                            'display_name' => 'Create Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ],
                        [
                            'name' => 'create-timetable-assignment',
                            'display_name' => 'Create Timetable Assignment',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ],
                        [
                            'name' => 'clone-timetable',
                            'display_name' => 'Clone Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ],
                        [
                            'name' => 'edit-timetable',
                            'display_name' => 'Edit Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ],
                        [
                            'name' => 'delete-timetable',
                            'display_name' => 'Delete Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ],
                        [
                            'name' => 'publish-timetable',
                            'display_name' => 'Publish Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ],
                        [
                            'name' => 'generate-timetable',
                            'display_name' => 'Generate Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ],
                        [
                            'name' => 'save-change-timetable',
                            'display_name' => 'Save Change Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ],
                        [
                            'name' => 'print-timetable',
                            'display_name' => 'Print Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ],
                        [
                            'name' => 'export-timetable',
                            'display_name' => 'Export Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Timetable Slot Management',
                    'groups' => [],
                    'permissions' => [
                        [
                            'name' => 'drag-course-session',
                            'display_name' => 'Drag Course Session',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management', 'create-timetable'
                            ]
                        ],
                        [
                            'name' => 'remove-timetable-slot',
                            'display_name' => 'Remove Timetable Slot',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management', 'edit-timetable'
                            ]
                        ],
                        [
                            'name' => 'resize-timetable-slot',
                            'display_name' => 'Resize Timetable Slot',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management', 'edit-timetable'
                            ]
                        ],
                        [
                            'name' => 'move-timetable-slot',
                            'display_name' => 'Move Timetable Slot',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management', 'edit-timetable'
                            ]
                        ],
                        [
                            'name' => 'add-room-timetable-slot',
                            'display_name' => 'Add Room To Timetable Slot',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ],
                        [
                            'name' => 'remove-room-timetable-slot',
                            'display_name' => 'Remove Room From Timetable Slot',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Teacher Timetable Management',
                    'groups' => [],
                    'permissions' => [
                        [
                            'name' => 'teacher-view-timetable',
                            'display_name' => 'Teacher View Timetable',
                            'dependency' => [
                                'view-backend'
                            ]
                        ],
                        [
                            'name' => 'teacher-edit-timetable',
                            'display_name' => 'Teacher Edit Timetable',
                            'dependency' => [
                                'view-backend'
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Type Timetable Management',
                    'groups' => [],
                    'permissions' => [
                        [
                            'name' => 'global-timetable',
                            'display_name' => 'Global Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management', 'create-timetable'
                            ]
                        ],
                        [
                            'name' => 'personal-timetable',
                            'display_name' => 'Personal Timetable',
                            'dependency' => [
                                'view-backend', 'view-schedule-management', 'view-timetable-management', 'create-timetable'
                            ]
                        ]
                    ]
                ],
            ],
            'permissions' => [
                [
                    'name' => 'view-schedule-management',
                    'display_name' => 'View Schedule Management',
                    'dependency' => [
                        'view-backend'
                    ]
                ]

            ]

        ];

        $this->saveGroupPemission($roots, null);

    }

    private function saveGroupPemission($groupPermission, $parent_id)
    {
        for ($a = 0; $a < count($groupPermission); $a++) {
            $permission = $groupPermission[$a];

            // Save group first
            $group_model = config('access.group');
            $group = $group_model::where('name', $permission['name'])->first();
            if ($group == null) $group = new $group_model;

            $group->name = $permission['name'];
            $group->sort = $a + 1; // Sort start from 1
            $group->parent_id = $parent_id;
            $group->created_at = Carbon::now();
            $group->updated_at = Carbon::now();
            $group->save();

            // Save permissions for current group second
            for ($b = 0; $b < count($permission['permissions']); $b++) {

                $child = $permission['permissions'][$b];

                $permission_model = config('access.permission');
                $p = $permission_model::where('name', $child['name'])->first();
                if ($p == null) $p = new $permission_model;

                $p->display_name = $child['display_name'];
                $p->name = $child['name'];
                $p->system = true;
                $p->group_id = $group->id;
                $p->sort = $b + 1;
                $p->created_at = Carbon::now();
                $p->updated_at = Carbon::now();
                $p->save();

                // Permission dependency
                if (count($child['dependency']) > 0) {
                    foreach ($child['dependency'] as $dependency) {
                        $permission_id = $p->id;
                        $dependency_id = DB::table('permissions')->where('name', $dependency)->first()->id;

                        // check if already have dependency
                        $db_dependency = DB::table(config('access.permission_dependencies_table'))->where(['permission_id' => $permission_id, 'dependency_id' => $dependency_id])->first();
                        // Insert new if there isn't
                        if ($db_dependency == null) {
                            DB::table(config('access.permission_dependencies_table'))->insert([
                                'permission_id' => $permission_id,
                                'dependency_id' => $dependency_id,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                        }
                    }
                }
            }

            // Save Child groups third, and repeat
            if (!empty($permission['groups'])) {
                $this->saveGroupPemission($permission['groups'], $group->id);
            }
        }
    }
}
