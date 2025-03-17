<?php

namespace App\Services\Property;

use App\Repositories\PropertyRepository;
use App\DTOs\PropertySearchCriteria;

class PropertySearchService
{
    private PropertyRepository $propertyRepository;
    private array $searchStrategies;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
        $this->searchStrategies = [
            new StatusSearchStrategy(),
            new InvestmentSearchStrategy(),
            new RoomSearchStrategy(),
            new TypeSearchStrategy(),
            new AreaSearchStrategy(),
            new ExcludePropertiesStrategy(),
            new BuildingSearchStrategy(),
        ];
    }

    public function search(PropertySearchCriteria $criteria)
    {
        $query = $this->propertyRepository->query();

        foreach ($this->searchStrategies as $strategy) {
            $query = $strategy->apply($query, $criteria);
        }

        return $query->get();
    }
} 