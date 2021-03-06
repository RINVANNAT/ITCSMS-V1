<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Strings Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in strings throughout the system.
    | Regardless where it is placed, a string can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'backend' => [
        'access' => [
            'permissions' => [
                'edit_explanation' => 'If you performed operations in the hierarchy section without refreshing this page, you will need to refresh to reflect the changes here.',

                'groups' => [
                    'hierarchy_saved' => 'Hierarchy successfully saved.',
                ],

                'sort_explanation' => 'This section allows you to organize your permissions into groups to stay organized. Regardless of the group, the permissions are still individually assigned to each role.',
            ],

            'users' => [
                'delete_user_confirm' => 'Are you sure you want to delete this user permanently? Anywhere in the application that references this user\'s id will most likely error. Proceed at your own risk. This can not be un-done.',
                'if_confirmed_off' => '(If confirmed is off)',
                'restore_user_confirm' => 'Restore this user to its original state?',
            ],
        ],

        'dashboard' => [
            'title' => 'Administrative Dashboard',
            'welcome' => 'Welcome',
        ],

        'general' => [
            'all_rights_reserved' => 'All Rights Reserved.',
            'are_you_sure' => 'Are you sure?',
            'boilerplate_link' => 'Laravel 5 Boilerplate',
            'continue' => 'Continue',
            'member_since' => 'Member since',
            'search_placeholder' => 'Search...',

            'see_all' => [
                'messages' => 'See all messages',
                'notifications' => 'View all',
                'tasks' => 'View all tasks',
            ],

            'status' => [
                'online' => 'Online',
                'offline' => 'Offline',
            ],

            'you_have' => [
                'messages' => '{0} You don\'t have messages|{1} You have 1 message|[2,Inf] You have :number messages',
                'notifications' => '{0} You don\'t have notifications|{1} You have 1 notification|[2,Inf] You have :number notifications',
                'tasks' => '{0} You don\'t have tasks|{1} You have 1 task|[2,Inf] You have :number tasks',
            ],
        ],

        'course_annual' => [
            'no_student_record' => ' This course is not yet created for any students please check your course again if something go wrong!',
            'wrong_option' => 'Please Choose the correct department -> option-> degree -> grade ! Then click the button refresh!'
        ],
        'timetable' => [
            'in_progress' => 'You can start create timetable.',
            'waiting' => 'You\'re not allowed to create timetable yet',
            'finished' => 'You have no more time to create timetable.',
            'start_at' => 'You can start at ',
            'start_from' => 'You can start from ',
            'message_waiting' => 'From now, you can\'t create timetable. Please wait till your turn',
            'message_finished' => 'If you want to create timetable, please contact to ',
            'message_progress' => 'Now, you can create timetable. Please make sure that you finish your work on time.',
            'status' => 'Status',
            'to' => 'To',
            'from' => 'Form',
            // create timetable, course section.
            'block_drag_course_session' => 'Dragging course session is blocked',
            'desc_block_drag_course_session' => 'You are not allow to drag course session. Please contact to Study Office to get more information.',
            // create timetable, room section
            'block_add_room' => 'Adding room is blocked',
            'desc_block_add_room' => 'You are not allow to add room. Please contact to Study Office to get more information.',
            'search_room' => 'SEARCH ROOM'
        ]
    ],

    'emails' => [
        'auth' => [
            'password_reset_subject' => 'Your Password Reset Link',
            'reset_password' => 'Click here to reset your password',
        ],
    ],

    'frontend' => [
        'email' => [
            'confirm_account' => 'Click here to confirm your account:',
        ],

        'test' => 'Test',

        'tests' => [
            'based_on' => [
                'permission' => 'Permission Based - ',
                'role' => 'Role Based - ',
            ],

            'js_injected_from_controller' => 'Javascript Injected from a Controller',

            'using_blade_extensions' => 'Using Blade Extensions',

            'using_access_helper' => [
                'array_permissions' => 'Using Access Helper with Array of Permission Names or ID\'s where the user does have to possess all.',
                'array_permissions_not' => 'Using Access Helper with Array of Permission Names or ID\'s where the user does not have to possess all.',
                'array_roles' => 'Using Access Helper with Array of Role Names or ID\'s where the user does have to possess all.',
                'array_roles_not' => 'Using Access Helper with Array of Role Names or ID\'s where the user does not have to possess all.',
                'permission_id' => 'Using Access Helper with Permission ID',
                'permission_name' => 'Using Access Helper with Permission Name',
                'role_id' => 'Using Access Helper with Role ID',
                'role_name' => 'Using Access Helper with Role Name',
            ],

            'view_console_it_works' => 'View console, you should see \'it works!\' which is coming from FrontendController@index',
            'you_can_see_because' => 'You can see this because you have the role of \':role\'!',
            'you_can_see_because_permission' => 'You can see this because you have the permission of \':permission\'!',
        ],

        'user' => [
            'profile_updated' => 'Profile successfully updated.',
            'password_updated' => 'Password successfully updated.',
        ],

        'welcome_to' => 'Welcome to :place',
    ],
];