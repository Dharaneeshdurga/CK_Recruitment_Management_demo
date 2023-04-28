<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Po_master_details extends Model
{
    protected $fillable = [
        'pmID',
        'medical_insurance',
        'accident_coverage',
        'term_insurance',
        'staff_welfare',
        'hr_software_modules',
        'internet_charges_wfh',
        'pf_admin_charge_percent',
        'hepl_bs_charge_percent',
    ];
}
