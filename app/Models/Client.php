<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';
    protected $attributes = [
        'source' => 1,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'lastname',
        'user_id',
        'mail',
        'mail2',
        'phone',
        'phone2',
        'source',
        'source_additional',
        'status',
        'deal_additional',
        'room',
        'area',
        'purpose',
        'budget',
        'nip',
        'pesel',
        'id_type',
        'id_number',
        'id_issued_by',
        'id_issued_date' ,
        'is_company',
        'company_name',
        'regon',
        'krs',
        'address',
        'exponent',
        'city',
        'post_code',
        'street',
        'house_number',
        'apartment_number',
        'martial_status',
        'email_tracking_id',
    ];

    public function offers()
    {
        return $this->hasMany(Offer::class, 'client_id', 'id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'client_id', 'id');
    }

    /**
     * Get the preferences associated with the client.
     */
    public function preferences()
    {
        return $this->hasMany(ClientPreference::class, 'client_id', 'id');
    }

    public function seller()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'contact_id', 'id');
    }

    public function dealsFields(): HasMany
    {
        return $this->hasMany(ClientFields::class);
    }
}
