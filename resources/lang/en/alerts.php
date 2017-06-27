<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Alert Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain alert messages for various scenarios
    | during CRUD operations. You are free to modify these language lines
    | according to your application's requirements.
    |
    */
    

    'backend' => [
        'generals'=> [
            'updated' => 'Information is updated.',
            'deleted' => 'Information is deleted',
            'created' => 'Information is created',
            'no_result_found' => 'There are no records.'
        ],
        'permissions' => [
            'created' => 'Permission successfully created.',
            'deleted' => 'Permission successfully deleted.',

            'groups'  => [
                'created' => 'Permission group successfully created.',
                'deleted' => 'Permission group successfully deleted.',
                'updated' => 'Permission group successfully updated.',
            ],

            'updated' => 'Permission successfully updated.',
        ],

        'roles' => [
            'created' => 'The role was successfully created.',
            'deleted' => 'The role was successfully deleted.',
            'updated' => 'The role was successfully updated.',
        ],

        'users' => [
            'confirmation_email' => 'A new confirmation e-mail has been sent to the address on file.',
            'created' => 'The user was successfully created.',
            'deleted' => 'The user was successfully deleted.',
            'deleted_permanently' => 'The user was deleted permanently.',
            'restored' => 'The user was successfully restored.',
            'updated' => 'The user was successfully updated.',
            'updated_password' => "The user's password was successfully updated.",
        ],

        'course_annual' => [
            'score' => [
                'clone' => [
                    'miss_some_score' => 'Some Student Score are successfully clone! Please ask responsible person to input score to all students!',
                    'not_input_yet' => 'Sorry the score is depending on Department: :dept '.' and score are not finished  inputing. <br> Please ask them to input the score quickly or wait to clone next time! :)',
                    'no_reference_course' => 'Cannot Clone! Because a reference program is not selected!'. '<br> Please update your course annual by selecting the REFERENCE-COURSE attribute!',
                    'no_respsonsible_dept' =>  'This is a wrong action! You cannot make a clone score request because your department course is not in Vocational'.'<br>'.'Please set the Right Responsibility Department to your course!!',
                    'not_a_vocational' => 'This is a wrong action! You cannot make a clone score request because your department course is not in Vocational',
                    'success' => ' Score Successfully Cloned !',
                    'different_percentage' => 'Danger!!! Your course score is not martch with the targetted course! ('. ':percent'.') Please check your percentage of the course before you can clone!',
                    'no_score_applied' => 'Sorry! There are no scores for cloning. Please wait unitil the responsible department :dept complete them!!'
                ]
            ]
        ]
    ],
];