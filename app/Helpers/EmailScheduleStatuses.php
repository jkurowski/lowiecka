<?php
namespace App\Helpers;

class EmailScheduleStatuses
{
    const PENDING = 'pending';
    const SENT = 'sent';
    const FAILED = 'failed';
    const NO_CONSENT = 'no_consent';

    public static function getStatuses(): array
    {
        return [
            self::PENDING => 'Oczekuje',
            self::SENT => 'Wysłane',
            self::FAILED => 'Błąd',
            self::NO_CONSENT => 'Brak zgody RODO',
        ];
    }

    public static function getStatus(string $status): string
    {
        return self::getStatuses()[$status];
    }
}
