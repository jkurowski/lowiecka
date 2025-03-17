<?php

namespace App\Models;

use App\Helpers\RoomStatusMaper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientFields extends Model
{
    use HasFactory;

    protected $table = 'client_deal_fields';
    protected $fillable = [
        'client_id',
        'status',
        'deal_additional',
        'investment_id',
        'room',
        'area',
        'budget',
        'purpose',
        'property_id',
        'storage_id',
        'parking_id'
    ];

    protected $casts = [
        'area' => 'decimal:2',
        'budget' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class);
    }

}
