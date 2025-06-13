<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvFileJobRecuirtment extends Model
{
    protected $table = 'cv_file_job_recuirtment';

    protected $fillable = [
        'cv_file_id',
        'job_recuirtment_id',
        'score',
        'reason',
        'status',
        'position_match',
        'experience',
        'skills',
        'education',
        'location_match',
        'industry_fit',
    ];
} 