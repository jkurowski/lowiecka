<?php

namespace App\Providers;

use App\Events\ClientDealsFieldsCreated;
use App\Events\ClientDealsFieldsDeleted;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// CMS
use App\Listeners\SendPropertyStatusChangedNotification;
use App\Listeners\SetPropertiesFree;
use App\Listeners\SetPropertiesReserved;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'property.status.changed' => [
            SendPropertyStatusChangedNotification::class,
        ],
        ClientDealsFieldsDeleted::class => [
            SetPropertiesFree::class,
        ],
        ClientDealsFieldsCreated::class => [
            SetPropertiesReserved::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \App\Models\Client::observe(\App\Observers\ClientStatusObserver::class);
    }
}
