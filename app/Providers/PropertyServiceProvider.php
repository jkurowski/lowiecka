<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\PropertyRepository;
use App\Repositories\Interfaces\PropertyRepositoryInterface;

class PropertyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PropertyRepositoryInterface::class, PropertyRepository::class);
    }
} 