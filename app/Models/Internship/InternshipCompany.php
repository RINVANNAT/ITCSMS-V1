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

    protected $appends = [
        'text'
    ];
    
    public function internships ()
    {
        return $this->hasMany(Internship::class, 'company_id', 'id');
    }

    public function getTextAttribute()
    {
        return $this->name ." (".$this->title.")";
    }
}
