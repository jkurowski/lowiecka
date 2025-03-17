<?php

namespace App\Services\Property;

use App\DTOs\PropertySearchCriteria;
use App\Services\Property\Interfaces\SearchStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class ExcludePropertiesStrategy implements SearchStrategyInterface
{
    public function apply(Builder $query, PropertySearchCriteria $criteria): Builder
    {
        if ($criteria->getExcludePropertyIds()) {
            $query->whereNotIn('id', $criteria->getExcludePropertyIds());
        }

        return $query;
    }
} 