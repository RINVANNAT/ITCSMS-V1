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



    const EN_SECTION_HEADER = [

        ['', 'Student ID', 'Student Name', 'Sexe',

            ['label' => 'Absences', 'colspan' => 3],
            ['label' => 'Competency', 'colspan' => 4],
            'Toal Score'
        ],
        ['', '', '', '',
            ['label' => 'Total', 'colspan' => 1],
            ['label' => 'S_1', 'colspan' => 1],
            ['label' => 'S_2', 'colspan' => 1],
            ['label' => 'Speaking', 'colspan' => 1],
            ['label' => 'Writing', 'colspan' => 1],
            ['label' => 'Listening', 'colspan' => 1],
            ['label' => 'Reading', 'colspan' => 1],
            ''
        ]
    ];

    protected $en_header;
    public function __construct()
    {
        $this->en_header = ProficencyEnum::EN_SECTION_HEADER;
    }

    static function ENGLISHHEADER() {



        return json_encode(json_decode(json_encode(ProficencyEnum::EN_SECTION_HEADER, true), true));
    }

}