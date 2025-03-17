<?php

namespace App\Helpers;

class ClientSalesStatuses
{
    public const INITIAL_CONTACT = 1;
    public const OFFER_SENT = 2;
    public const MEETING_SCHEDULED = 3;
    public const NEGOTIATIONS = 4;
    public const RESERVATION_AGREEMENT = 5;
    public const DEVELOPER_AGREEMENT = 6;
    public const PRE_SALE_AGREEMENT = 11;
    public const OWNERSHIP_TRANSFER = 9;
    public const TENANT_CHANGES = 7;
    public const TECHNICAL_ACCEPTANCE = 8;
    public const PREMISES_HANDOVER = 10;

    private const STATUS_MAP = [
        self::INITIAL_CONTACT => 'Kontakt wstępny',
        self::OFFER_SENT => 'Wysłana oferta',
        self::MEETING_SCHEDULED => 'Umówione spotkanie',
        self::NEGOTIATIONS => 'Negocjacje',
        self::RESERVATION_AGREEMENT => 'Umowa rezerwacyjna',
        self::DEVELOPER_AGREEMENT => 'Umowa deweloperska',
        self::PRE_SALE_AGREEMENT => 'Umowa przedsprzedażowa',
        self::OWNERSHIP_TRANSFER => 'Umowa przeniesienia własności',
        self::TENANT_CHANGES => 'Zmiany lokatorskie',
        self::TECHNICAL_ACCEPTANCE => 'Odbiór techniczny',
        self::PREMISES_HANDOVER => 'Wydanie lokalu',
    ];

    public static function getStatusText(int $statusValue): ?string
    {
        return self::STATUS_MAP[$statusValue] ?? null;
    }

    public static function getAllStatuses(): array
    {
        return array_map(
            fn ($value, $text) => ['value' => $value, 'text' => $text],
            array_keys(self::STATUS_MAP),
            self::STATUS_MAP
        );
    }


}
