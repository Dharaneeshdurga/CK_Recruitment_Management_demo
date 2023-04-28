<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer_release_followup_details extends Model
{
    protected $fillable = [
        'orfID',
        'cdID',
        'rfh_no',
        'hepl_recruitment_ref_number',
        'description',
        'created_by',
    ];
}
