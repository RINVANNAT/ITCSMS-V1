<?php

return [
    'backend' => [
        'timetable' => [
            'assignment_permission' => [
                'modal_title' => 'Timetable Assignment',
                'modal_body' => [
                    'form' => [
                        'department' => [
                            'label' => 'Department',
                            'placeholder' => 'Department'
                        ],
                        'datetime' => [
                            'label' => 'Datetime',
                            'placeholder' => 'Datetime'
                        ],
                        'submit' => [
                            'assign' => 'Assign'
                        ]
                    ]
                ]
            ],
            'update_assignment_permission' => [
                'modal_title' => 'Edit Timetable Assignment',
                'modal_body' => [
                    'form' => [
                        'datetime' => [
                            'label' => 'Datetime',
                            'placeholder' => 'Datetime'
                        ],
                        'submit' => [
                            'update' => 'Update'
                        ]
                    ]
                ]
            ],
            ''
        ]
    ]
];