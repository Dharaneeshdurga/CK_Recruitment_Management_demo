<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate_po_mail extends Model
{
    protected $fillable = [
        'cdID',
        'client_type',
        'to_mail',
        'cc_mail',
        'subject',
        'message',
        'created_on',

    ];
}
