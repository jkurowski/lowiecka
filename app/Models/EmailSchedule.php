<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSchedule extends Model
{
    use HasFactory;



    protected $fillable = [
        'email_type',
        'email_address',
        'user_id',
        'scheduled_date',
        'status',
        'template_id',
        'subject',
        'content',
        'action'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
    ];
}
