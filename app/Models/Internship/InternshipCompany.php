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
    
    public function internships ()
    {
        return $this->hasMany(Internship::class, 'company_id', 'id');
    }
}
