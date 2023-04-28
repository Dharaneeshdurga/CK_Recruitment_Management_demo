<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer_released_details extends Model
{
    protected $fillable = [
        'orID',
        'cdID',
        'rfh_no',
        'hepl_recruitment_ref_number',
        'closed_date',
        'closed_salary',
        'salary_review',
        'joining_type',
        'date_of_joining',
        'remark',
        'profile_status',
        'created_by',
        'created_on',
        
    ];
}
