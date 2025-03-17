<?php

namespace App\Helpers;

class PropertyAreaTypes {
  const ROOM_APARTMENT = 1;
  const STORAGE = 2;
  const PARKING = 3;  

  private const STATUS_MAP = [
    self::ROOM_APARTMENT => 'Mieszkanie / Apartament',
    self::STORAGE => 'Komórka lokatorska',
    self::PARKING => 'Miejsce parkingowe',
];

  public static function getStatusText(int $statusValue): ?string
  {
    return self::STATUS_MAP[$statusValue] ?? null;
  }

  public static function getAll(): array
  {
    return self::STATUS_MAP;
  }
}