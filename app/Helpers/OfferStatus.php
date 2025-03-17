<?php

if (! function_exists('offerStatus')) {
    function offerStatus(int $number)
    {
        switch ($number) {
            case '1':
                return "Wysłana";
            case '2':
                return "Szkic";
            case '3':
                return "Przeczytana";
            case '4':
                return "Gotowa do wysyłki";
            case '5':
                return "Kopia";
        }
    }
}