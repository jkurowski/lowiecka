<?php

namespace App\Helpers;

class EmailScheduleTypes
{
  const NEWSLETTER = '10';
  const OFFER = '20';

  public static function getTypes(): array
  {
    return [
      self::NEWSLETTER => 'Newsletter',
      self::OFFER => 'Oferta',
    ];
  }
}
