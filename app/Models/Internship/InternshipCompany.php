<?php

namespace App\Models\Internship;

use Illuminate\Database\Eloquent\Model;

class InternshipCompany extends Model
{
    protected $table="internship_companies";

    protected $fillable = [
        'name',
        'title',
        'training_field',
        'address',
        'phone',
        'hp',
        'mail',
        'web'
    ];
}
