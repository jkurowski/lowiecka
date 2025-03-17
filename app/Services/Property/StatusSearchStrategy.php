<?php

namespace App\Services\Property;

use App\DTOs\PropertySearchCriteria;
use App\Services\Property\Interfaces\SearchStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class StatusSearchStrategy implements SearchStrategyInterface
{
    public function apply(Builder $query, PropertySearchCriteria $criteria): Builder
    {
        $query->where('status', $criteria->getStatus());
        $query->whereNull('client_id');

        return $query;
    }
}