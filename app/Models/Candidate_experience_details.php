<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate_experience_details extends Model
{
    protected $fillable = [
        'cdID',
        'rfh_no',
        'hepl_recruitment_ref_number',
        'job_title',
        'company_name',
        'exp_start_month',
        'exp_start_year',
        'exp_end_month',
        'exp_end_year',
        'certificate',
        'created_on',
        
    ];
}
