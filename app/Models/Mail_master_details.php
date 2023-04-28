<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail_master_details extends Model
{
    protected $fillable = [
        'mID',
        'step',
        'mail_subject',
        'mail_body_content',
        'mail_footer',
        'remark',
        'business_type',
    ];
}
