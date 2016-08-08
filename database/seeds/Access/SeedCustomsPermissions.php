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
        $permissions[] = [
            'name'=>'Office',
            'sort'=>1,
            'childs'=>[
                [
                    'permission'=>'list-office',
                    'name' => 'List Office'
                ],
                [
                    'permission'=>'create-office',
                    'name' => 'Create Office'
                ],[
                    'permission'=>'delete-office',
                    'name' => 'Delete Office'
                ],[
                    'permission'=>'update-office',
                    'name' => 'Update Office'
                ],[
                    'permission'=>'assign-employee-office',
                    'name' => 'Assign Employee Office'
                ],[
                    'permission'=>'unassign-employee-office',
                    'name' => 'Unassign Employee Office'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Professional',
            'sort'=>2,
            'childs'=>[
                [
                    'permission'=>'create-professional',
                    'name' => 'Create Professional'
                ],[
                    'permission'=>'delete-professional',
                    'name' => 'Delete Professional'
                ],[
                    'permission'=>'update-professional',
                    'name' => 'Update Professional'
                ],[
                    'permission'=>'assign-employee-professional',
                    'name' => 'Assign Employee Professional'
                ],[
                    'permission'=>'unassign-employee-professional',
                    'name' => 'Unassign Employee Professional'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Course',
            'sort'=>3,
            'childs'=>[
                [
                    'permission'=>'create-course',
                    'name' => 'Create Course'
                ],[
                    'permission'=>'delete-course',
                    'name' => 'Delete Course'
                ],[
                    'permission'=>'update-course',
                    'name' => 'Update Course'
                ],[
                    'permission'=>'assign-employee-course',
                    'name' => 'Assign Employee Course'
                ],[
                    'permission'=>'unassign-employee-course',
                    'name' => 'Unassign Employee Course'
                ],[
                    'permission'=>'view-course',
                    'name' => 'View Course'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Education',
            'sort'=>4,
            'childs'=>[
                [
                    'permission'=>'list-education',
                    'name' => 'List Education'
                ],[
                    'permission'=>'create-education',
                    'name' => 'Create Education'
                ],[
                    'permission'=>'delete-education',
                    'name' => 'Delete Education'
                ],[
                    'permission'=>'update-education',
                    'name' => 'Update Education'
                ],[
                    'permission'=>'assign-employee-education',
                    'name' => 'Assign Employee Education'
                ],[
                    'permission'=>'unassign-employee-education',
                    'name' => 'Unassign Employee Education'
                ],[
                    'permission'=>'view-education',
                    'name' => 'View Education'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Children',
            'sort'=>5,
            'childs'=>[
                [
                    'permission'=>'list-children',
                    'name' => 'List Children'
                ],[
                    'permission'=>'create-children',
                    'name' => 'Create Children'
                ],[
                    'permission'=>'delete-children',
                    'name' => 'Delete Children'
                ],[
                    'permission'=>'update-children',
                    'name' => 'Update Children'
                ],[
                    'permission'=>'view-children',
                    'name' => 'View Children'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Spouse',
            'sort'=>6,
            'childs'=>[
                [
                    'permission'=>'list-spouse',
                    'name' => 'List Spouse'
                ],[
                    'permission'=>'create-spouse',
                    'name' => 'Create Spouse'
                ],[
                    'permission'=>'delete-spouse',
                    'name' => 'Delete Spouse'
                ],[
                    'permission'=>'update-spouse',
                    'name' => 'Update Spouse'
                ],[
                    'permission'=>'view-spouse',
                    'name' => 'View Spouse'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Mission',
            'sort'=>7,
            'childs'=>[
                [
                    'permission'=>'list-mission',
                    'name' => 'List Mission'
                ],[
                    'permission'=>'create-mission',
                    'name' => 'Create Mission'
                ],[
                    'permission'=>'delete-mission',
                    'name' => 'Delete Mission'
                ],[
                    'permission'=>'update-mission',
                    'name' => 'Update Mission'
                ],[
                    'permission'=>'view-mission',
                    'name' => 'view Mission'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Censure',
            'sort'=>8,
            'childs'=>[
                [
                    'permission'=>'list-censure',
                    'name' => 'List Censure'
                ],[
                    'permission'=>'create-censure',
                    'name' => 'Create Censure'
                ],[
                    'permission'=>'delete-censure',
                    'name' => 'Delete Censure'
                ],[
                    'permission'=>'update-censure',
                    'name' => 'Update Censure'
                ],[
                    'permission'=>'view-censure',
                    'name' => 'View Censure'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Place',
            'sort'=>9,
            'childs'=>[
                [
                    'permission'=>'list-place',
                    'name' => 'List Place'
                ],[
                    'permission'=>'create-place',
                    'name' => 'Create Place'
                ],[
                    'permission'=>'delete-place',
                    'name' => 'Delete Place'
                ],[
                    'permission'=>'update-place',
                    'name' => 'Update Place'
                ],[
                    'permission'=>'view-place',
                    'name' => 'View Place'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Job',
            'sort'=>10,
            'childs'=>[
                [
                    'permission'=>'list-job',
                    'name' => 'List Job'
                ],[
                    'permission'=>'create-job',
                    'name' => 'Create Job'
                ],[
                    'permission'=>'delete-job',
                    'name' => 'Delete Job'
                ],[
                    'permission'=>'update-job',
                    'name' => 'Update Job'
                ],[
                    'permission'=>'view-job',
                    'name' => 'View Job'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Rank',
            'sort'=>10,
            'childs'=>[
                [
                    'permission'=>'list-rank',
                    'name' => 'List Rank'
                ],[
                    'permission'=>'create-rank',
                    'name' => 'Create Rank'
                ],[
                    'permission'=>'delete-rank',
                    'name' => 'Delete Rank'
                ],[
                    'permission'=>'update-rank',
                    'name' => 'Update Rank'
                ],[
                    'permission'=>'view-rank',
                    'name' => 'View Rank'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Employee',
            'sort'=>10,
            'childs'=>[
                [
                    'permission'=>'list-employee',
                    'name' => 'List Employee'
                ],[
                    'permission'=>'list-retirement-employee',
                    'name' => 'List Retirment Employee'
                ],[
                    'permission'=>'create-employee',
                    'name' => 'Create Employee'
                ],[
                    'permission'=>'delete-employee',
                    'name' => 'Delete Employee'
                ],[
                    'permission'=>'update-employee',
                    'name' => 'Update Employee'
                ],[
                    'permission'=>'view-employee',
                    'name' => 'View Employee'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Reward',
            'sort'=>10,
            'childs'=>[
                [
                    'permission'=>'list-reward',
                    'name' => 'List Reward'
                ],[
                    'permission'=>'create-reward',
                    'name' => 'Create Reward'
                ],[
                    'permission'=>'delete-reward',
                    'name' => 'Delete Reward'
                ],[
                    'permission'=>'update-reward',
                    'name' => 'Update Reward'
                ],[
                    'permission'=>'view-reward',
                    'name' => 'View Reward'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Organization',
            'sort'=>11,
            'childs'=>[
                [
                    'permission'=>'list-organization',
                    'name' => 'List Organization'
                ],[
                    'permission'=>'create-organization',
                    'name' => 'Create Organization'
                ],[
                    'permission'=>'delete-organization',
                    'name' => 'Delete Organization'
                ],[
                    'permission'=>'update-organization',
                    'name' => 'Update Organization'
                ],[
                    'permission'=>'view-organization',
                    'name' => 'View Organization'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Institute',
            'sort'=>11,
            'childs'=>[
                [
                    'permission'=>'list-institute',
                    'name' => 'List Institute'
                ],[
                    'permission'=>'create-institute',
                    'name' => 'Create Institute'
                ],[
                    'permission'=>'delete-institute',
                    'name' => 'Delete Institute'
                ],[
                    'permission'=>'update-institute',
                    'name' => 'Update Institute'
                ],[
                    'permission'=>'view-institute',
                    'name' => 'View Institute'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Certificate',
            'sort'=>12,
            'childs'=>[
                [
                    'permission'=>'list-certificate',
                    'name' => 'List Certificate'
                ],[
                    'permission'=>'create-certificate',
                    'name' => 'Create Certificate'
                ],[
                    'permission'=>'delete-certificate',
                    'name' => 'Delete Certificate'
                ],[
                    'permission'=>'update-certificate',
                    'name' => 'Update Certificate'
                ],[
                    'permission'=>'view-certificate',
                    'name' => 'view Certificate'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Decider',
            'sort'=>12,
            'childs'=>[
                [
                    'permission'=>'list-decider',
                    'name' => 'List Decider'
                ],[
                    'permission'=>'create-decider',
                    'name' => 'Create Decider'
                ],[
                    'permission'=>'delete-decider',
                    'name' => 'Delete Decider'
                ],[
                    'permission'=>'update-decider',
                    'name' => 'Update Decider'
                ],[
                    'permission'=>'view-decider',
                    'name' => 'view Decider'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Evaluation',
            'sort'=>13,
            'childs'=>[
                [
                    'permission'=>'list-evaluation',
                    'name' => 'List Evaluation'
                ],
                [
                    'permission'=>'view-evaluation',
                    'name' => 'View Evaluation'
                ],
                [
                    'permission'=>'create-evaluation',
                    'name' => 'Create Evaluation'
                ],[
                    'permission'=>'delete-evaluation',
                    'name' => 'Delete Evaluation'
                ],[
                    'permission'=>'update-evaluation',
                    'name' => 'Update Evaluation'
                ]
            ]
        ];
        $permissions[] = [
            'name'=>'Attendance',
            'sort'=>14,
            'childs'=>[
                [
                    'permission'=>'list-attendance',
                    'name' => 'List Attendance'
                ],[
                    'permission'=>'view-attendance',
                    'name' => 'View Attendance'
                ],
                [
                    'permission'=>'create-attendance',
                    'name' => 'Create Attendance'
                ],[
                    'permission'=>'delete-attendance',
                    'name' => 'Delete Attendance'
                ],[
                    'permission'=>'update-attendance',
                    'name' => 'Update Attendance'
                ]
            ]
        ];
        foreach($permissions as $index => $permission){

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
