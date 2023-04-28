<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer_released_later_date extends Model
{
    protected $fillable = [
        'orldID',
        'cdID',
        'rfh_no',
        'hepl_recruitment_ref_number',
        'orladj_resignation_received',
        'orladj_touchbase',
        'initiate_backfil',
        'created_by',
        'created_on',
    ];
}
