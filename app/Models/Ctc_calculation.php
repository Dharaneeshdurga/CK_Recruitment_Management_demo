<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ctc_calculation extends Model
{
    protected $fillable = [
        'ctcID',
        'cdID',
        'rfh_no',
        'hepl_recruitment_ref_number',
        'basic_pm',
        'basic_pa',
        'hra_pm',
        'hra_pa',
        'medi_al_pm',
        'medi_al_pa',
        'conv_pm',
        'conv_pa',
        'spl_al_pm',
        'spl_al_pa',
        'comp_a_pm',
        'comp_a_pa',
        'ec_pf_pm',
        'ec_pf_pa',
        'ec_esi_pm',
        'ec_esi_pa',
        'sub_totalb_pm',
        'sub_totalb_pa',
        'gratuity_pm',
        'gratuity_pa',
        'st_bonus_pm',
        'st_bonus_pa',
        'sub_totalc_pm',
        'sub_totalc_pa',
        'abc_pm',
        'abc_pa',
        'net_pay',
        'created_by',
        'modified_by',
    ];
}
