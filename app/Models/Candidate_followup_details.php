<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate_followup_details extends Model
{
    protected $fillable = [
        'cfdID',
        'cdID',
        'rfh_no',
        'follow_up_status',
        'created_on',
        'created_by',
        
    ];
}
