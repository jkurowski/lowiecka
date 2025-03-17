<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_id',
        'name',
        'number',
        'html',
        'cords',
        'file',
        'file_webp',
        'meta_title',
        'meta_description',
        'meta_robots',
        'active',
        'content',
        'area_range',
        'rooms_range',
        'price_range'
    ];

    /**
     * Get building floors
     * @return HasMany
     */
    public function floors(): HasMany
    {
        return $this->hasMany('App\Models\Floor');
    }

    /**
     * Get building floors with properties count
     * @return Collection
     */
    public function floorsWithCount(): Collection
    {
        return $this->hasMany('App\Models\Floor')->withCount('properties')->get(['id', 'name']);
    }

    /**
     * Get building properties
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany('App\Models\Property');
    }

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function ($building) {
            $building->floors()->each(function($floor) {
                $floor->delete();
            });
        });
    }
}
