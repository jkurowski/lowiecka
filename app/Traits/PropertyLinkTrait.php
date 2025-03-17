<?php

namespace App\Traits;

use App\Models\Property;

trait PropertyLinkTrait
{

  public function getLinkToProperty(Property $property)
  {

    $investmentType = $property->investment->type;
    switch ($investmentType) {
      case 1:
        return route($this->getPropertyRouteByInvestmentType($investmentType), [$property->investment_id, $property->building_id, $property->floor_id, $property->id]);
      case 2:
        return route($this->getPropertyRouteByInvestmentType($investmentType), [$property->investment_id, $property->floor->id, $property->id]);
      case 3:
        return route($this->getPropertyRouteByInvestmentType($investmentType), [$property->investment_id, $property->id]);
      default:
        return null;
    }
  }

  public function getPropertyRouteByInvestmentType(int $investmentType)
  {
    switch ($investmentType) {
      case 1: // Osiedlowa
        return 'admin.developro.investment.building.floor.properties.edit';
      case 2: // Budynkowa
        return 'admin.developro.investment.properties.edit';
      case 3: // Z domami
        return 'admin.developro.investment.houses.edit';
      default:
        return null;
    }
  }
}
