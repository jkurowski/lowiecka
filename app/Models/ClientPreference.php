<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPreference extends Model
{
    protected $fillable = [
        'client_id',
        'investment_id',
        'city_id',
        'status',
        'apartment',
        'area_min',
        'area_max',
        'rooms',
        'budget',
        'purpose',
        'note'
    ];

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }
}
