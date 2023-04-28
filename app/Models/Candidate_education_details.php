<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate_education_details extends Model
{
    protected $fillable = [
        'cdID',
        'rfh_no',
        'hepl_recruitment_ref_number',
        'degree',
        'university',
        'edu_start_month',
        'edu_start_year',
        'edu_end_month',
        'edu_end_year',
        'certificate',
        'created_on',
        
    ];

}
