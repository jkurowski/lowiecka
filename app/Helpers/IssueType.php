<?php

if (! function_exists('issueType')) {
    function issueType(int $number)
    {
        switch ($number) {
            case '5':
                return "Zmiana lokatorska";
            case '7':
                return "Usterka";
            case '8':
                return "Rękojmia";
        }
    }
}