<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeDetails extends Model
{
    protected $fillable = [
        'cldID',
        'college_name',
    ];
}
