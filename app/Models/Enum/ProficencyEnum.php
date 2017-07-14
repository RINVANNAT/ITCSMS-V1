<?php
/**
 * Created by PhpStorm.
 * User: vannat
 * Date: 7/11/17
 * Time: 1:45 PM
 */

namespace App\Models\Enum;


use Illuminate\Support\Facades\Response;

class ProficencyEnum
{



    const DEFAULT_HEADER = [

        ['', 'Student ID', 'Student Name', 'Sexe',

            ['label' => 'Absences', 'colspan' => 3],
            ['label' => 'Competency', 'colspan' => 4],
            'Toal Score'
        ],
        [
            '',
            '',
            '',
            ''

        ]
    ];

}