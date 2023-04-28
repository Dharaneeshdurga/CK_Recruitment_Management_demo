<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raisedby_list extends Model
{
    protected $fillable = [
        'rbID',
        'raised_by',
        'status',
        'created_by',
        'created_on',
    ];
}
