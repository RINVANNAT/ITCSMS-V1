<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in Exceptions thrown throughout the system.
    | Regardless where it is placed, a button can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'backend' => [
        'general' => [
            'has_reference' => 'This record has reference with other data. You can not delete this first',
            'delete_error' => 'Something went wrong. Cannot delete this record',
            'already_exists' => 'This record is already exist. You cannot add the same record',
            'create_error' => 'There was a problem creating this record. Please try again.',
            'not_found' => 'That record does not exist.',
            'no_permission' => 'You are not allow to do this operation. Contact your administrator to resolve this'
        ],
        'access' => [
            'permissions' => [
                'create_error' => 'There was a problem creating this permission. Please try again.',
                'delete_error' => 'There was a problem deleting this permission. Please try again.',

                'groups' => [
                    'associated_permissions' => 'You can not delete this group because it has associated permissions.',
                    'has_children' => 'You can not delete this group because it has child groups.',
                    'name_taken' => 'There is already a group with that name',
                ],

                'not_found' => 'That permission does not exist.',
                'system_delete_error' => 'You can not delete a system permission.',
                'update_error' => 'There was a problem updating this permission. Please try again.',
            ],

            'roles' => [
                'already_exists' => 'That role already exists. Please choose a different name.',
                'cant_delete_admin' => 'You can not delete the Administrator role.',
                'create_error' => 'There was a problem creating this role. Please try again.',
                'delete_error' => 'There was a problem deleting this role. Please try again.',
                'has_users' => 'You can not delete a role with associated users.',
                'needs_permission' => 'You must select at least one permission for this role.',
                'not_found' => 'That role does not exist.',
                'update_error' => 'There was a problem updating this role. Please try again.',
            ],

            'users' => [
                'cant_deactivate_self' => 'You can not do that to yourself.',
                'cant_delete_self' => 'You can not delete yourself.',
                'create_error' => 'There was a problem creating this user. Please try again.',
                'delete_error' => 'There was a problem deleting this user. Please try again.',
                'email_error' => 'That email address belongs to a different user.',
                'mark_error' => 'There was a problem updating this user. Please try again.',
                'not_found' => 'That user does not exist.',
                'restore_error' => 'There was a problem restoring this user. Please try again.',
                'role_needed_create' => 'You must choose at lease one role. User has been created but deactivated.',
                'role_needed' => 'You must choose at least one role.',
                'update_error' => 'There was a problem updating this user. Please try again.',
                'update_password_error' => 'There was a problem changing this users password. Please try again.',
            ],
        ],

        'configuration' => [
            'departments' => [
                'create_error' => 'There was a problem creating this department. Please try again.',
                'delete_error' => 'There was a problem deleting this department. Please try again.',
                'not_found' => 'That department does not exist.',
                'already_exists' => 'That department already exists. You can not add the same department.',
            ],
            'degrees' => [
                'create_error' => 'There was a problem creating this degree. Please try again.',
                'delete_error' => 'There was a problem deleting this degree. Please try again.',
                'not_found' => 'That degree does not exist.',
                'already_exists' => 'That degree already exists. You can not add the same degree.',
            ],
            'grades' => [
                'create_error' => 'There was a problem creating this grade. Please try again.',
                'delete_error' => 'There was a problem deleting this grade. Please try again.',
                'not_found' => 'That grade does not exist.',
                'already_exists' => 'That grade already exists. You can not add the same grade.',
            ],
            'academicYears' => [
                'create_error' => 'There was a problem creating this academic year. Please try again.',
                'delete_error' => 'There was a problem deleting this academic year. Please try again.',
                'not_found' => 'That academic year does not exist.',
                'already_exists' => 'That academic year already exists. You can not add the same academic year.',
            ],
            'accounts' => [
                'create_error' => 'There was a problem creating this account. Please try again.',
                'delete_error' => 'There was a problem deleting this account. Please try again.',
                'not_found' => 'That account does not exist.',
                'already_exists' => 'That account already exists. You can not add the same account.',
            ],
            'buildings' => [
                'create_error' => 'There was a problem creating this building. Please try again.',
                'delete_error' => 'There was a problem deleting this building. Please try again.',
                'not_found' => 'That building does not exist.',
                'already_exists' => 'That building already exists. You can not add the same building.',
            ],
            'highSchools' => [
                'create_error' => 'There was a problem creating this high school. Please try again.',
                'delete_error' => 'There was a problem deleting this high school. Please try again.',
                'not_found' => 'That high school does not exist.',
                'already_exists' => 'That high school already exists. You can not add the same high school.',
            ],
            'incomeTypes' => [
                'create_error' => 'There was a problem creating this income type. Please try again.',
                'delete_error' => 'There was a problem deleting this income type. Please try again.',
                'not_found' => 'That income type does not exist.',
                'already_exists' => 'That academic year already exists. You can not add the same academic year.',
            ],
            'outcomeTypes' => [
                'create_error' => 'There was a problem creating this outcome type. Please try again.',
                'delete_error' => 'There was a problem deleting this outcome type. Please try again.',
                'not_found' => 'That outcome type does not exist.',
                'already_exists' => 'That outcome type already exists. You can not add the same outcome type.',
            ],
            'rooms' => [
                'create_error' => 'There was a problem creating this room. Please try again.',
                'delete_error' => 'There was a problem deleting this room. Please try again.',
                'not_found' => 'That academic year does not exist.',
                'already_exists' => 'That room already exists. You can not add the same room.',
            ],
            'studentsBac2s' => [
                'create_error' => 'There was a problem creating this student BacII. Please try again.',
                'delete_error' => 'There was a problem deleting this student BacII. Please try again.',
                'not_found' => 'That student BacII does not exist.',
                'already_exists' => 'That student BacII already exists. You can not add the same student BacII.',
            ],
            'create_error' => 'There was a problem creating this configuration. Please try again.',
            'delete_error' => 'There was a problem deleting this configuration. Please try again.',
            'not_found' => 'That configuration does not exist.',
            'already_exists' => 'That configuration already exists. You can not add the same configuration.'
        ],
        'exams'=>[
            'has_candidate' => 'You cannot delete this examination. There are associated candidates!'
        ],
        'students' => [
            'already_exits' => "Student with this ID Card is already exist. Please check again or modify ID Card number"
        ]
    ],

    'frontend' => [
        'auth' => [
            'confirmation' => [
                'already_confirmed' => 'Your account is already confirmed.',
                'confirm' => 'Confirm your account!',
                'created_confirm' => 'Your account was successfully created. We have sent you an e-mail to confirm your account.',
                'mismatch' => 'Your confirmation code does not match.',
                'not_found' => 'That confirmation code does not exist.',
                'resend' => 'Your account is not confirmed. Please click the confirmation link in your e-mail, or <a href="' . route('account.confirm.resend', ':token') . '">click here</a> to resend the confirmation e-mail.',
                'success' => 'Your account has been successfully confirmed!',
                'resent' => 'A new confirmation e-mail has been sent to the address on file.',
            ],

            'deactivated' => 'Your account has been deactivated.',
            'email_taken' => 'That e-mail address is already taken.',

            'password' => [
                'change_mismatch' => 'That is not your old password.',
            ],


        ],
    ],
];