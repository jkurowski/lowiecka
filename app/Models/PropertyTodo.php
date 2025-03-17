<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyTodo extends Model
{
    protected $fillable = [
        'property_id',
        'user_id',
        'text',
        'completed',
        'due_date',
        'attachments',
        'x',
        'y'
    ];

    protected $casts = [
        'attachments' => 'array', // Automatically handle JSON encoding/decoding
        'completed' => 'boolean',
        'due_date' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for 'due_date'
    public function getDueDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
}
