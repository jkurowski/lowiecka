<?php

namespace App\Helpers;


class ContactOrigins
{



  public const CONTACT_FORM = 1;
  public const DIRECT_EMAIL = 2;
  public const PHONE = 3;
  public const CHAT_ONLINE = 4;
  public const SOCIAL_MEDIA = 5;
  public const ADVERTISING = 6;
  public const NEWSLETTER = 7;
  public const RECOMMENDATION = 8;
  public const EVENTS_AND_CONFERENCES = 9;
  public const PERSONAL_CONTACT = 10;


  private const STATUS_MAP = [
    self::CONTACT_FORM => 'Formularz kontaktowy',
    self::DIRECT_EMAIL => 'Bezpośrednia wiadomość e-mail',
    self::PHONE => 'Telefon',
    self::CHAT_ONLINE => 'Chat online',
    self::SOCIAL_MEDIA => 'Media społecznościowe',
    self::ADVERTISING => 'Reklama',
    self::NEWSLETTER => 'Newsletter',
    self::RECOMMENDATION => 'Rekomendacje',
    self::EVENTS_AND_CONFERENCES => 'Wydarzenia i konferencje',
    self::PERSONAL_CONTACT => 'Kontakt osobisty',
  ];

  public static function getStatusText(int $statusValue): ?string
  {
    return self::STATUS_MAP[$statusValue] ?? null;
  }

  public static function getAllStatuses(): array
  {
    return array_map(
      fn($value, $text) => ['value' => $value, 'text' => $text],
      array_keys(self::STATUS_MAP),
      self::STATUS_MAP
    );
  }

  public static function getStatusesForSelect(): array
  {
    return array_column(self::getAllStatuses(), 'text', 'value');
  }
}
