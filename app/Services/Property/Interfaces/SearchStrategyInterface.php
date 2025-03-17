<?php

namespace App\Services\Property\Interfaces;

use App\DTOs\PropertySearchCriteria;
use Illuminate\Database\Eloquent\Builder;

interface SearchStrategyInterface
{
    public function apply(Builder $query, PropertySearchCriteria $criteria): Builder;
} 