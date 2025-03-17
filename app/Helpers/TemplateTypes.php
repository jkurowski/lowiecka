<?php

namespace App\Helpers;

class TemplateTypes
{
    const EMPTY = '0';
    const EMAIL = '10';
    const OFFER = '20';
    const NOT_EDITABLE = '30';
    const NEWSLETTER = '40';

    public static function getTypes(): array
    {
        return [
            self::EMPTY => 'Brak',
            self::EMAIL => 'Email',
            self::OFFER => 'Oferta',
            self::NEWSLETTER => 'Newsletter',
        ];
    }

    public static function mapTypeToLayout(string $type): string
    {
        return match ($type) {
            self::EMPTY => 'Brak',
            self::EMAIL => 'Email',
            self::OFFER => 'Oferta',
            self::NEWSLETTER => 'Newsletter',
            default => 'Brak',
        };
    }
}
