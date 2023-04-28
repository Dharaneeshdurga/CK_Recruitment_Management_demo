<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University_list extends Model
{
    protected $fillable = [
        'uID',
        'state',
        'university_name',
    ];
}