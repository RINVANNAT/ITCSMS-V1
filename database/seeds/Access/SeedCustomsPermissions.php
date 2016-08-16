<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
            'name'=>'Access',
            'groups'=>[
                [
                    'name'=>'User',
                    'sort'=>1,
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name'         => 'create-users',
                            'display_name' => 'Create Users',
                        ],
                        [
                            'name'         => 'edit-users',
                            'display_name' => 'Edit Users',
                        ],
                        [
                            'name'         => 'delete-users',
                            'display_name' => 'Delete Users',
                        ],
                        [
                            'name'         => 'change-user-password',
                            'display_name' => 'Change User Password',
                        ],
                        [
                            'name'         => 'deactivate-users',
                            'display_name' => 'Deactivate Users',
                        ],
                        [
                            'name'         => 'reactivate-users',
                            'display_name' => 'Re-Activate Users',
                        ],
                        [
                            'name'         => 'undelete-users',
                            'display_name' => 'Restore Users',
                        ],
                        [
                            'name'         => 'permanently-delete-users',
                            'display_name' => 'Permanently Delete Users',
                        ],
                        [
                            'name'         => 'resend-user-confirmation-email',
                            'display_name' => 'Resend Confirmation E-mail',
                        ],
                    ]
                ],
                [
                    'name'=>'Role',
                    'sort'=>2,
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name'         => 'create-roles',
                            'display_name' => 'Create Roles',
                        ],
                        [
                            'name'         => 'edit-roles',
                            'display_name' => 'Edit Roles',
                        ],
                        [
                            'name'         => 'delete-roles',
                            'display_name' => 'Delete Roles',
                        ]
                    ]
                ],
                [
                    'name'=>'Permission',
                    'sort'=>3,
                    'groups' => [

                    ],
                    'permissions' => [
                        /* Permissions Group */
                        [
                            'name'         => 'create-permission-groups',
                            'display_name' => 'Create Permission Groups',
                        ],
                        [
                            'name'         => 'edit-permission-groups',
                            'display_name' => 'Edit Permission Groups',
                        ],
                        [
                            'name'         => 'delete-permission-groups',
                            'display_name' => 'Delete Permission Groups',
                        ],
                        [
                            'name'         => 'sort-permission-groups',
                            'display_name' => 'Sort Permission Groups',
                        ],
                        /* Permissions */
                        [
                            'name'         => 'create-permissions',
                            'display_name' => 'Create Permissions',
                        ],
                        [
                            'name'         => 'edit-permissions',
                            'display_name' => 'Edit Permissions',
                        ],
                        [
                            'name'         => 'delete-permissions',
                            'display_name' => 'Delete Permissions',
                        ]
                    ]
                ]
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name'         => 'view-backend',
                    'display_name' => 'View Backend',
                ],
                [
                    'name'         => 'view-access-management',
                    'display_name' => 'View Access Management',
                ]
            ]
        ];

        /* Student affair permissions */
        $roots[] = [
            'name'=>'Student & Study Affair',
            'sort'=>2,
            'groups'=>[
                [
                    'name'=>'Student',
                    'sort'=>1,
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name'         => 'view-student-management',
                            'display_name' => 'View Student Management',
                        ],
                        [
                            'name'         => 'create-students',
                            'display_name' => 'Create Students',
                        ],
                        [
                            'name'         => 'edit-students',
                            'display_name' => 'Edit Students',
                        ],
                        [
                            'name'         => 'delete-students',
                            'display_name' => 'Delete Students',
                        ]
                    ]
                ],
                [
                    'name'=>'Candidate',
                    'sort'=>2,
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name'         => 'view-candidate-management',
                            'display_name' => 'View Candidate Management',
                        ],
                        [
                            'name'         => 'create-candidates',
                            'display_name' => 'Create Candidates',
                        ],
                        [
                            'name'         => 'edit-candidates',
                            'display_name' => 'Edit Candidates',
                        ],
                        [
                            'name'         => 'delete-candidates',
                            'display_name' => 'Edit Candidates',
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
            'name'=>'Accounting',
            'sort'=>3,
            'groups'=>[
                [
                    'name'=>'Income',
                    'sort'=>1,
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name'         => 'view-income-management',
                            'display_name' => 'View Income Management',
                        ],
                        [
                            'name'         => 'create-incomes',
                            'display_name' => 'Create Incomes',
                        ],
                        [
                            'name'         => 'edit-incomes',
                            'display_name' => 'Edit Incomes',
                        ],
                        [
                            'name'         => 'delete-incomes',
                            'display_name' => 'Delete Incomes',
                        ],
                    ]
                ],
                [
                    'name'=>'Outcome',
                    'sort'=>2,
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name'         => 'view-outcome-management',
                            'display_name' => 'View Outcome Management',
                        ],
                        [
                            'name'         => 'create-outcomes',
                            'display_name' => 'Create Outcomes',
                        ],
                        [
                            'name'         => 'edit-outcomes',
                            'display_name' => 'Edit Outcomes',
                        ],
                        [
                            'name'         => 'delete-outcomes',
                            'display_name' => 'Edit Outcomes',
                        ],
                    ]
                ],
                [
                    'name'=>'Student Payment',
                    'sort'=>3,
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name'         => 'view-student-payment-management',
                            'display_name' => 'View Student Payment Management',
                        ]

                    ]
                ],
                [
                    'name'=>'Customer',
                    'sort'=>4,
                    'groups' => [

                    ],
                    'permissions' => [
                        [
                            'name'         => 'view-customer-management',
                            'display_name' => 'View Customer Management',
                        ],
                        [
                            'name'         => 'create-customers',
                            'display_name' => 'Create Customers',
                        ],
                        [
                            'name'         => 'edit-customers',
                            'display_name' => 'Edit Customers',
                        ],
                        [
                            'name'         => 'delete-customers',
                            'display_name' => 'Delete Customers',
                        ]
                    ]
                ]
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name'         => 'view-accounting-management',
                    'display_name' => 'View Accounting Management',
                ]
            ]
        ];

        /* Scholarship Permissions */
        $roots[] = [
            'name'=>'Scholarship',
            'sort'=>4,
            'groups'=>[
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name'         => 'view-scholarship-management',
                    'display_name' => 'View Scholarship Management',
                ],
                [
                    'name'         => 'create-scholarships',
                    'display_name' => 'Create Scholarships',
                ],
                [
                    'name'         => 'edit-scholarships',
                    'display_name' => 'Edit Scholarships',
                ],
                [
                    'name'         => 'delete-scholarships',
                    'display_name' => 'Delete Scholarships',
                ]
            ]
        ];

        /* Examination Permissions */
        $roots[] = [
            'name'=>'Examination',
            'sort'=>5,
            'groups'=>[
            ],
            'permissions' => [
                // Leave it empty if there is none

                [
                    'name'         => 'view-exam-management',
                    'display_name' => 'View Exam Management',
                ],
                [
                    'name'         => 'create-exams',
                    'display_name' => 'Create Exams',
                ],
                [
                    'name'         => 'edit-exams',
                    'display_name' => 'Edit Exams',
                ],
                [
                    'name'         => 'delete-exams',
                    'display_name' => 'Delete Exams',
                ]
            ]
        ];

        /* Course Permissions */
        $roots[] = [
            'name'=>'Course',
            'sort'=>6,
            'groups'=>[
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name'         => 'view-course-management',
                    'display_name' => 'View Course Management',
                ],
                [
                    'name'         => 'create-courses',
                    'display_name' => 'Create Courses',
                ],
                [
                    'name'         => 'edit-courses',
                    'display_name' => 'Edit Courses',
                ],
                [
                    'name'         => 'delete-courses',
                    'display_name' => 'Delete Courses',
                ]
            ]
        ];

        /* Employee Permissions */
        $roots[] = [
            'name'=>'Employee',
            'sort'=>7,
            'groups'=>[
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name'         => 'view-employee-management',
                    'display_name' => 'View Employee Management',
                ],
                [
                    'name'         => 'create-employees',
                    'display_name' => 'Create Employees',
                ],
                [
                    'name'         => 'edit-employees',
                    'display_name' => 'Edit Employees',
                ],
                [
                    'name'         => 'delete-employees',
                    'display_name' => 'Delete Employees',
                ]
            ]
        ];

        /* Inventory Permissions */
        $roots[] = [
            'name'=>'Inventory',
            'sort'=>8,
            'groups'=>[
            ],
            'permissions' => [
                // Leave it empty if there is none
                [
                    'name'         => 'view-inventory-management',
                    'display_name' => 'View Inventory Management',
                ],
                [
                    'name'         => 'create-inventories',
                    'display_name' => 'Create Inventories',
                ],
                [
                    'name'         => 'edit-inventories',
                    'display_name' => 'Edit Inventories',
                ],
                [
                    'name'         => 'delete-inventories',
                    'display_name' => 'Delete Inventories',
                ]
            ]
        ];

        /* Configuration Permissions */
        $roots[] = [
            'name'=>'Configuration',
            'sort'=>9,
            'groups'=>[
                [
                    'name'=>'Department',
                    'sort'=>1,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-department-management',
                            'display_name' => 'View Department Management',
                        ],
                        [
                            'name'         => 'create-departments',
                            'display_name' => 'Create Departments',
                        ],
                        [
                            'name'         => 'edit-departments',
                            'display_name' => 'Edit Departments',
                        ],
                        [
                            'name'         => 'delete-departments',
                            'display_name' => 'Delete Departments',
                        ]
                    ]
                ],
                [
                    'name'=>'Degree',
                    'sort'=>2,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-degree-management',
                            'display_name' => 'View Degree Management',
                        ],
                        [
                            'name'         => 'create-degrees',
                            'display_name' => 'Create Degrees',
                        ],
                        [
                            'name'         => 'edit-degrees',
                            'display_name' => 'Edit Degrees',
                        ],
                        [
                            'name'         => 'delete-degrees',
                            'display_name' => 'Delete Degrees',
                        ]
                    ]
                ],
                [
                    'name'=>'Grade',
                    'sort'=>3,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-grade-management',
                            'display_name' => 'View Grade Management',
                        ],
                        [
                            'name'         => 'create-grades',
                            'display_name' => 'Create Grades',
                        ],
                        [
                            'name'         => 'edit-grades',
                            'display_name' => 'Edit Grades',
                        ],
                        [
                            'name'         => 'delete-grades',
                            'display_name' => 'Delete Grades',
                        ]
                    ]
                ],
                [
                    'name'=>'Academic Year',
                    'sort'=>4,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-academicYear-management',
                            'display_name' => 'View Academic Year Management',
                        ],
                        [
                            'name'         => 'create-academicYears',
                            'display_name' => 'Create Academic Years',
                        ],
                        [
                            'name'         => 'edit-academicYears',
                            'display_name' => 'Edit Academic Years',
                        ],
                        [
                            'name'         => 'delete-academicYears',
                            'display_name' => 'Delete Academic Years',
                        ]
                    ]
                ],
                [
                    'name'=>'Account',
                    'sort'=>5,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-account-management',
                            'display_name' => 'View Account Management',
                        ],
                        [
                            'name'         => 'create-accounts',
                            'display_name' => 'Create Accounts',
                        ],
                        [
                            'name'         => 'edit-accounts',
                            'display_name' => 'Edit Accounts',
                        ],
                        [
                            'name'         => 'delete-accounts',
                            'display_name' => 'Delete Accounts',
                        ]
                    ]
                ],
                [
                    'name'=>'Building',
                    'sort'=>6,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-building-management',
                            'display_name' => 'View Building Management',
                        ],
                        [
                            'name'         => 'create-buildings',
                            'display_name' => 'Create Buildings',
                        ],
                        [
                            'name'         => 'edit-buildings',
                            'display_name' => 'Edit Buildings',
                        ],
                        [
                            'name'         => 'delete-buildings',
                            'display_name' => 'Delete Buildings',
                        ]
                    ]
                ],
                [
                    'name'=>'High School',
                    'sort'=>7,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-highSchool-management',
                            'display_name' => 'View High School Management',
                        ],
                        [
                            'name'         => 'create-highSchools',
                            'display_name' => 'Create High Schools',
                        ],
                        [
                            'name'         => 'edit-highSchools',
                            'display_name' => 'Edit High Schools',
                        ],
                        [
                            'name'         => 'delete-highSchools',
                            'display_name' => 'Delete High Schools',
                        ]
                    ]
                ],
                [
                    'name'=>'Income Type',
                    'sort'=>8,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-incomeType-management',
                            'display_name' => 'View Income Type Management',
                        ],
                        [
                            'name'         => 'create-incomeTypes',
                            'display_name' => 'Create Income Types',
                        ],
                        [
                            'name'         => 'edit-incomeTypes',
                            'display_name' => 'Edit Income Types',
                        ],
                        [
                            'name'         => 'delete-incomeTypes',
                            'display_name' => 'Delete Income Types',
                        ]
                    ]
                ],
                [
                    'name'=>'Outcome Type',
                    'sort'=>9,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-outcomeType-management',
                            'display_name' => 'View Outcome Type Management',
                        ],
                        [
                            'name'         => 'create-outcomeTypes',
                            'display_name' => 'Create Outcome Types',
                        ],
                        [
                            'name'         => 'edit-outcomeTypes',
                            'display_name' => 'Edit Outcome Types',
                        ],
                        [
                            'name'         => 'delete-outcomeTypes',
                            'display_name' => 'Delete Outcome Types',
                        ]
                    ]
                ],
                [
                    'name'=>'School & Scholarship Fee',
                    'sort'=>10,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-schoolFee-management',
                            'display_name' => 'View School Fee Management',
                        ],
                        [
                            'name'         => 'create-schoolFees',
                            'display_name' => 'Create School Fees',
                        ],
                        [
                            'name'         => 'edit-schoolFees',
                            'display_name' => 'Edit School Fees',
                        ],
                        [
                            'name'         => 'delete-schoolFees',
                            'display_name' => 'Delete School Fees',
                        ]
                    ]
                ],
                [
                    'name'=>'Room',
                    'sort'=>11,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-room-management',
                            'display_name' => 'View Room Management',
                        ],
                        [
                            'name'         => 'create-rooms',
                            'display_name' => 'Create Rooms',
                        ],
                        [
                            'name'         => 'edit-rooms',
                            'display_name' => 'Edit Rooms',
                        ],
                        [
                            'name'         => 'delete-rooms',
                            'display_name' => 'Delete Rooms',
                        ]
                    ]
                ],
                [
                    'name'=>'Room Type',
                    'sort'=>12,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-roomType-management',
                            'display_name' => 'View Room Type Management',
                        ],
                        [
                            'name'         => 'create-roomTypes',
                            'display_name' => 'Create Room Types',
                        ],
                        [
                            'name'         => 'edit-roomTypes',
                            'display_name' => 'Edit Room Types',
                        ],
                        [
                            'name'         => 'delete-roomTypes',
                            'display_name' => 'Delete Room Types',
                        ]
                    ]
                ],
                [
                    'name'=>'Student BacII',
                    'sort'=>13,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-studentBac2-management',
                            'display_name' => 'View Student BacII Management',
                        ],
                        [
                            'name'         => 'create-studentBac2s',
                            'display_name' => 'Create Student BacII',
                        ],
                        [
                            'name'         => 'edit-studentBac2s',
                            'display_name' => 'Edit Student BacII',
                        ],
                        [
                            'name'         => 'delete-studentBac2s',
                            'display_name' => 'Delete Student BacII',
                        ]
                    ]
                ],
                [
                    'name'=>'Department Option/ Technique',
                    'sort'=>14,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-departmentOption-management',
                            'display_name' => 'View Department Option Management',
                        ],
                        [
                            'name'         => 'create-departmentOptions',
                            'display_name' => 'Create Department Options',
                        ],
                        [
                            'name'         => 'edit-departmentOptions',
                            'display_name' => 'Edit Department Options',
                        ],
                        [
                            'name'         => 'delete-departmentOptions',
                            'display_name' => 'Delete Department Options',
                        ]
                    ]
                ],
                [
                    'name'=>'Promotion',
                    'sort'=>15,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-promotion-management',
                            'display_name' => 'View Promotion Management',
                        ],
                        [
                            'name'         => 'create-promotions',
                            'display_name' => 'Create Promotions',
                        ],
                        [
                            'name'         => 'edit-promotions',
                            'display_name' => 'Edit Promotions',
                        ],
                        [
                            'name'         => 'delete-promotions',
                            'display_name' => 'Delete Promotions',
                        ]
                    ]
                ],
                [
                    'name'=>'Redouble',
                    'sort'=>16,
                    'groups'=>[
                    ],
                    'permissions' => [
                        // Leave it empty if there is none
                        [
                            'name'         => 'view-redouble-management',
                            'display_name' => 'View Redouble Management',
                        ],
                        [
                            'name'         => 'create-redoubles',
                            'display_name' => 'Create Redoubles',
                        ],
                        [
                            'name'         => 'edit-redoubles',
                            'display_name' => 'Edit Redoubles',
                        ],
                        [
                            'name'         => 'delete-redoubles',
                            'display_name' => 'Delete Redoubles',
                        ]
                    ]
                ],

            ],
            'permissions' => [
                // Permissions for configuration
                // Leave it empty if there is none
                [
                    'name'         => 'view-configuration-management',
                    'display_name' => 'View Configuration Management',
                ]
            ]
        ];

        foreach($roots as $index => $permission){

            $group_model        = config('access.group');
            $group             = $group_model::updateOrCreate(['name'=>$permission['name']]);
            $group->sort       = $permission['sort'];
            $group->created_at = Carbon::now();
            $group->updated_at = Carbon::now();
            $group->save();


            $sort = 0;
            foreach($permission['childs'] as $key => $child){
                $sort++;
                $permission_model= config('access.permission');
                $p               = $permission_model::updateOrCreate(['name'=>$child['permission']]);
                $p->display_name = $child['name'];
                $p->system       = true;
                $p->group_id     = $group->id;
                $p->sort         = $sort;
                $p->created_at   = Carbon::now();
                $p->updated_at   = Carbon::now();
                $p->save();
            }
        }

    }
}
