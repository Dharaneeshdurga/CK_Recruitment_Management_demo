<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customusers extends Model
{
    protected $fillable = [
        'empID',
        'username',
        'passcode',
        'email',
        'role_type',
        'pre_onboarding',
        'active',
        'Induction_mail',
        'Buddy_mail',
    ];
}
