<?php

namespace App\Helpers;

class ContractorType
{
    public const GENERAL_CONTRACTOR = 1;
    public const NOTARY_LAWYER = 2;
    public const ARCHITECT_SURVEYOR = 3;
    public const OFFICE = 4;
    public const BANK = 5;
    public const WORKS_CONTRACTOR = 6;
    public const INTERMEDIARY_PORTALS = 7;
    public const ADVERTISING_AGENCY = 8;

    private const TYPE_MAP = [
        self::GENERAL_CONTRACTOR => 'Generalny Wykonawca',
        self::NOTARY_LAWYER => 'Notariusz/Prawnik',
        self::ARCHITECT_SURVEYOR => 'Architekt/Geodeta',
        self::OFFICE => 'Urząd',
        self::BANK => 'Bank',
        self::WORKS_CONTRACTOR => 'Wykonawca robót',
        self::INTERMEDIARY_PORTALS => 'Pośrednik/Portale',
        self::ADVERTISING_AGENCY => 'Agencja reklamowa'
    ];

    public static function getType(int $statusValue): ?string
    {
        return self::TYPE_MAP[$statusValue] ?? null;
    }

    public static function getAllTypes(): array
    {
        return array_map(
            fn($value, $text) => ['value' => $value, 'text' => $text],
            array_keys(self::TYPE_MAP),
            self::TYPE_MAP
        );
    }

    public static function getStatusesForSelect(): array
    {
        return array_column(self::getAllTypes(), 'text', 'value');
    }
}
