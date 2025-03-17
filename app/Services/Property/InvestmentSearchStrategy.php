<?php

namespace App\Services\Property;

use App\DTOs\PropertySearchCriteria;
use App\Services\Property\Interfaces\SearchStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class InvestmentSearchStrategy implements SearchStrategyInterface
{
    public function apply(Builder $query, PropertySearchCriteria $criteria): Builder
    {
        if ($criteria->getInvestmentId()) {
            $query->where('investment_id', $criteria->getInvestmentId());
        }

        return $query;
    }
} 