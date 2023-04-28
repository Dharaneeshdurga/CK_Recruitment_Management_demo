<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate_benefits_details extends Model
{
    protected $fillable = [
        'cdID',
        'rfh_no',
        'hepl_recruitment_ref_number',
        'doc_type',
        'doc_filename',
        'created_on',
        
    ];
}
