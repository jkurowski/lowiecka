<?php

namespace App\Providers;

use App\Repositories\Property\PropertyRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PropertyRepository::class);
    }
} 