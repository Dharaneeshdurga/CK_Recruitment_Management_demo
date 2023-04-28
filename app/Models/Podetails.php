<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podetails extends Model
{
    protected $fillable = [
        'poID',
        'cdID',
        'rfh_no',
        'hepl_recruitment_ref_number',
        'po_detail',
        'po_description',
        'po_amount',
        'remark',
        'po_remark',
        'created_by',
    ];
}
