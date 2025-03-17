<?php

if (! function_exists('clientPurposeStatus')) {
    function clientPurposeStatus(int $number)
    {
        switch ($number) {
            case '1':
                return "<span style=\"color:#3498db\">Kontakt wstępny</span>";
            case '2':
                return "<span style=\"color:#2ecc71\">Wysłana oferta</span>";
            case '3':
                return "<span style=\"color:#e74c3c\">Rezygnacja z oferty</span>";
            case '4':
                return "<span style=\"color:#1abc9c\">Zainteresowany wysłaną ofertą</span>";
            case '5':
                return "<span style=\"color:#f39c12\">Kontakt odroczony</span>";
        }
    }
}
