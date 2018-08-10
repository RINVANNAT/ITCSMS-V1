<?php

namespace App\Http\Controllers\Backend\StudentTrait;


use App\Models\DefineAverage;

trait PassedScoredTrait
{
    public function getPassedScores ($academic_year_id, $department_id)
    {
        $result = [];
        $passedScoreI = 50;
        $passedScoreII = 50;

        $passedScores = [$passedScoreI, $passedScoreII];

        foreach ($passedScores as $key => &$passedScore) {
            $tmp = DefineAverage::where([
                'academic_year_id' => $academic_year_id,
                'department_id' => $department_id,
                'semester_id' => $key+1
            ])->first();

            if ($tmp instanceof DefineAverage) {
                $passedScore = $tmp->value;
            } else {
                $passedScore = 50;
            }

            $result['passedScore' . ($key == 0 ? 'I' : 'II')] = $passedScore;
        }

        $result['passedScoreFinal'] = ($passedScores[0] + $passedScores[1])/2;

        return $result;
    }
}