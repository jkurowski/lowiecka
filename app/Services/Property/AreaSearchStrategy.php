<?php

namespace App\Services\Property;

use App\DTOs\PropertySearchCriteria;
use App\Services\Property\Interfaces\SearchStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class AreaSearchStrategy implements SearchStrategyInterface
{
    public function apply(Builder $query, PropertySearchCriteria $criteria): Builder
    {
        if ($criteria->getAreaRange()) {
            [$min, $max] = explode('-', $criteria->getAreaRange());
            
            $query->whereBetween('area', [
                (float) $min,
                (float) $max
            ]);
        }

        return $query;
    }
} 