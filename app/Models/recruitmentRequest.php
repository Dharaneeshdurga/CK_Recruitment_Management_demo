<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recruitmentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'recReqID',
        'rfh_no',
        'position_title',
        'sub_position_title',
        'no_of_position',
        'band',
        'open_date',
        'critical_position',
        'business',
        'division',
        'function',
        'location',
        'billing_status',
        'interviewer',
        'salary_range',
        'request_status',
        'close_date',
        'closed_by',
        'assigned_status',
        'assigned_to',
        'assigned_date',
        'hepl_recruitment_ref_number',
        'action_for_the_day_status',
        'created_by',
        'modified_by',
        'delete_status',
    ];

}
