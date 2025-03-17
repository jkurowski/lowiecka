<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_id',
        'building_id',
        'name',
        'number',
        'position',
        'type',
        'area_range',
        'price_range',
        'html',
        'cords',
        'file',
        'file_webp',
        'meta_title',
        'meta_description',
        'active'
    ];

    /**
     * Get floor properties
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany('App\Models\Property');
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function findNext(int $investment, int $id, int $building = null)
    {
        $next = $this->where('investment_id', $investment)->where('position', '>', $id)->orderBy('position');
        if ($building && $next) {
            $next->where('building_id', $building);
        }
        return $next->first();
    }

    public function findPrev(int $investment, int $id, int $building = null)
    {
        $prev = $this->where('investment_id', $investment)->where('position', '<', $id)->orderByDesc('position');
        if ($building && $prev) {
            $prev->where('building_id', $building);
        }
        return $prev->first();
    }

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function ($floor) {
            $floor->properties()->each(function($property) {
                $property->delete();
            });
        });
    }
}
