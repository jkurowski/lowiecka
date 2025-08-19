<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

use App\Traits\PropertyLinkTrait;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class Property extends Model
{
    use LogsActivity, Notifiable, PropertyLinkTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_id',
        'building_id',
        'floor_id',
        'storey',
        'storey_type',
        'storey_property',
        'status',
        'name',
        'name_list',
        'number',
        'number_order',
        'type',
        'rooms',
        'area',
        'area_search',
        'price',
        'price_brutto',
        'vat',
        'bank_account',
        'garden_area',
        'balcony_area',
        'balcony_area_2',
        'terrace_area',
        'loggia_area',
        'plot_area',
        'parking_space',
        'window',
        'garage',
        'type',
        'html',
        'cords',
        'file',
        'file_pdf',
        'file_webp',
        'file2',
        'file2_webp',
        'meta_title',
        'meta_description',
        'views',
        'active',
        'highlighted',
        'promotion_end_date',
        'promotion_price',
        'client_id',
        'saled_at',
        'reservation_until',


        'highlighted',
        'promotion_end_date',
        'promotion_price',
        'promotion_price_show',
        'visitor_related_type'
    ];

    /**
     * Get next property
     * @param int $investment
     * @param int $number_order
     * @return Property|null
     */
    public function findNext(int $investment_id, int $number_order, ?int $building_id = null, ?int $floor_id = null): ?Property
    {
        $query = $this->where('investment_id', $investment_id)->where('number_order', '>', $number_order);

        if (!is_null($building_id)) {
            $query->where('building_id', $building_id);
        }

        if (!is_null($floor_id)) {
            $query->where('floor_id', $floor_id);
        }

        return $query->first();
    }

    /**
     * Get previous property
     * @param int $investment
     * @param int $number_order
     * @return Property|null
     */
    public function findPrev(int $investment_id, int $number_order, ?int $building_id = null, ?int $floor_id = null): ?Property
    {
        $query = $this->where('investment_id', $investment_id)->where('number_order', '<', $number_order)->orderByDesc('number_order');

        if (!is_null($building_id)) {
            $query->where('building_id', $building_id);
        }

        if (!is_null($floor_id)) {
            $query->where('floor_id', $floor_id);
        }

        return $query->first();
    }

    /**
     * Get notifications for room
     * @return HasMany
     */
    public function roomsNotifications(): HasMany
    {
        return $this->hasMany(
            'App\Models\Notification',
            'notifiable_id',
            'id'
        )->where('notifiable_type', 'App\Models\Property')->latest();
    }

    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = new LogOptions();
        $logOptions->useLogName('Powierzchnia');
        $logOptions->logFillable();

        return $logOptions;
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function todos()
    {
        return $this->hasMany(PropertyTodo::class)->orderBy('created_at', 'desc');
    }

    public function getLocation(): string
    {
        $buildingName = $this->building ? $this->building->name : null;
        $floorName = $this->floor ? $this->floor->name : null;

        if ($buildingName && $floorName) {
            return "{$buildingName} - {$floorName}";
        } elseif ($floorName) {
            return "{$floorName}";
        }

        return 'Brak lokalizacji'; // Fallback if no building or floor is set
    }

    /**
     * Get the client that owns the property.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function relatedProperties()
    {
        return $this->belongsToMany(Property::class, 'property_property', 'property_id', 'related_property_id');
    }

    public function connectedProperty()
    {
        return $this->hasOne(Property::class, 'storey_property', 'id');
    }

    public function payments()
    {
        return $this->hasMany(PropertyPayment::class);
    }

    public function nextPaymentAfterToday()
    {
        return $this->payments()
            ->where('due_date', '>', Carbon::today())
            ->orderBy('due_date', 'asc')
            ->first();
    }

    public function investmentPayments()
    {
        return $this->hasManyThrough(InvestmentPayment::class, Investment::class, 'id', 'investment_id', 'investment_id', 'id');
    }

    // Define an accessor for the URL
    public function getUrlAttribute()
    {
        $investmentSlug = $this->investment->slug ?? '';
        return "/inwestycje/i/{$investmentSlug}/pietro/{$this->floor_id}/m/{$this->id}";
    }

    public function scopeFiltered($query, $filters)
    {
        if (!empty($filters['rooms'])) {
            $query->where('rooms', $filters['rooms']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['building'])) {
            $query->where('properties.building_id', $filters['building']);
        }
        if (!empty($filters['area'])) {
            [$min, $max] = explode('-', $filters['area']);
            $query->whereBetween('area', [(float) $min, (float) $max]);
        }
        if (!empty($filters['sort'])) {
            [$column, $direction] = explode(':', $filters['sort']);
            $query->orderBy($column, $direction);
        }

        return $query;
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($property) {
            if ($property->isDirty('status') && $property->getOriginal('status') == 2 && $property->status != 2) {
                event('property.status.changed', $property);
            }
        });
    }
}
