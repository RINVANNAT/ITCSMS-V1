<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateEntranceExamScores extends Model
{
    public $table = "candidateEntranceExamScores";

    public $fillable = [
        "score_c",
        "score_w",
        "score_na",
        "is_completed",
        "candidate_id",
        "corrector",
        "entrance_exam_course",
        "candidate_number_in_room"
    ];
}
