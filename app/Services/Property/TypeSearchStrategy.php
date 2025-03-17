<?php

namespace App\Services\Property;

use App\DTOs\PropertySearchCriteria;
use App\Services\Property\Interfaces\SearchStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class TypeSearchStrategy implements SearchStrategyInterface
{
    public function apply(Builder $query, PropertySearchCriteria $criteria): Builder
    {
        if ($criteria->getType()) {
            $query->where('type', $criteria->getType());
        }

        return $query;
    }
} 