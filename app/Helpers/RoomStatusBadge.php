<?php

use App\Helpers\RoomStatusMaper;

if (! function_exists('roomStatusBadge')) {
    function roomStatusBadge(int $number)
    {
        switch ($number) {
            case RoomStatusMaper::FREE:
                return '<span class="list-status list-status-1">Dostępne</span>';
            case RoomStatusMaper::RESERVED:
                return '<span class="list-status list-status-2">Rezerwacja</span>';
            case RoomStatusMaper::SOLD:
                return '<span class="list-status list-status-3">Sprzedane</span>';
            case RoomStatusMaper::RENTED:
                return '<span class="list-status list-status-4">Wynajęte</span>';
            case RoomStatusMaper::DEVELOPERS_AGREEMENT:
                return '<span class="list-status list-status-5">Umowa deweloperska</span>';
            case RoomStatusMaper::PRE_SALE_AGREEMENT:
                return '<span class="list-status list-status-6">Umowa przedsprzedażowa</span>';
        }
    }
}
