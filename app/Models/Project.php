<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'client_name',
        'unit_price',
        'start_date',
        'end_date',
        'status',
        'memo',
        'user_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'unit_price' => 'integer',
    ];
}
