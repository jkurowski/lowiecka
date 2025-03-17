<?php

if (! function_exists('clientPurpose')) {
    function clientPurpose(int $number)
    {
        switch ($number) {
            case '1':
                return "Prywatne";
            case '2':
                return "Inwestycyjne";
        }
    }
}
