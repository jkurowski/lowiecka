<?php

namespace App\Services\Property;

use App\DTOs\PropertySearchCriteria;
use App\Services\Property\Interfaces\SearchStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class BuildingSearchStrategy implements SearchStrategyInterface
{
    public function apply(Builder $query, PropertySearchCriteria $criteria): Builder
    {
        if ($criteria->getBuildingId()) {
            $query->where('building_id', '=',$criteria->getBuildingId());
        }
        return $query;
    }
}