<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Row extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'file_id',
        'row_id',
        'name',
        'date',
    ];
}
