<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateAnalyze extends Model
{
    protected $table = 'candidate_analyze';

    // Náº¿u báº¡n muá»‘n cho phÃ©p fill cÃ¡c trÆ°á»ng nÃ y qua create/update
    protected $fillable = [
        'cv_id',
        'fullname',
        'phone_number',
        'email',
        'other_contacts',
        'experience',
        'projects',
        'total_years_of_experience',
        'skills',
        'education',
        'proposition'
    ];
      // Thêm đoạn này!
    protected $casts = [
        'other_contacts' => 'array',
        'experience'     => 'array',
        'projects'       => 'array',
        'skills'         => 'array',
        'education'      => 'array',
    ];
}
