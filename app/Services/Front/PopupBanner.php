<?php

namespace App\Services\Front;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;

/**
 * Baner „na start" na stronie głównej (Ustawienia → Baner na start, storage/app/settings.json).
 *
 * Przeniesione z projektu poligonowa: okno czasowe od/do, „Tylko raz" pamiętane 30 dni
 * i odcisk treści w ciasteczku (zmiana treści pokazuje baner ponownie).
 * Dodane tutaj: czas wyświetlenia 0 = baner zamyka wyłącznie odwiedzający.
 */
class PopupBanner
{
    /** Jak długo pamiętamy, że ktoś widział ten baner (tryb „Tylko raz"). */
    private const REMEMBER_DAYS = 30;

    /** Wartość pola „Uruchom baner" oznaczająca „Za każdym razem". */
    private const MODE_ALWAYS = '1';

    public function __construct(
        public readonly string $key,
        public readonly string $text,
        public readonly int $timeout,
        public readonly bool $shouldOpen,
    ) {
    }

    public static function global(): ?self
    {
        if ((string) settings('popup_status') !== '1') {
            return null;
        }

        if (!self::isWithinSchedule(settings('popup_from'), settings('popup_until'))) {
            return null;
        }

        $text = (string) settings('popup_text');

        // Sama grafika też jest treścią - strip_tags usunąłby <img>.
        if (trim(strip_tags($text, '<img>')) === '') {
            return null;
        }

        return new self(
            'global',
            $text,
            max(0, (int) settings('popup_timeout')),
            self::resolveVisibility('global', (string) settings('popup_mode'), $text),
        );
    }

    /** Czy baner zamyka się sam, czy tylko krzyżykiem. */
    public function closesItself(): bool
    {
        return $this->timeout > 0;
    }

    /** Obie daty włącznie; puste lub niepoprawne pole = brak ograniczenia z tej strony. */
    private static function isWithinSchedule($from, $until): bool
    {
        $start = self::parseDate($from);

        if ($start !== null && $start->startOfDay()->isFuture()) {
            return false;
        }

        $end = self::parseDate($until);

        if ($end !== null && $end->endOfDay()->isPast()) {
            return false;
        }

        return true;
    }

    private static function parseDate($value): ?Carbon
    {
        if (blank($value)) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Ciasteczko trzyma skrót treści, nie samo „widziałem" - po podmianie grafiki
     * na nową kampanię baner pokaże się także tym, którzy widzieli poprzednią.
     */
    private static function resolveVisibility(string $key, string $mode, string $text): bool
    {
        $cookie = 'popup_seen_' . $key;

        if ($mode === self::MODE_ALWAYS) {
            Cookie::queue(Cookie::forget($cookie));

            return true;
        }

        $fingerprint = substr(md5($text), 0, 12);

        if (Cookie::get($cookie) === $fingerprint) {
            return false;
        }

        Cookie::queue($cookie, $fingerprint, self::REMEMBER_DAYS * 24 * 60);

        return true;
    }
}
