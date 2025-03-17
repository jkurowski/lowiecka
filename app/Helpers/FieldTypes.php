<?php

if (!function_exists('documentFieldTypes')) {
    function documentFieldTypes()
    {
        return [
            'text' => 'Pole tekstowe - krótkie',
            'textarea' => 'Pole tekstowe - długie',
            'date' => 'Pole z datą do edycji',
            'date-current' => 'Pole z aktualną datą',
            'client-name' => '[Klient] Imię i Nazwisko',
            'client-pesel' => '[Klient] PESEL',
            'client-id_number' => '[Klient] Nr. dowodu',
            'client-email' => '[Klient] E-mail',
            'client-phone' => '[Klient] Telefon',
            'client-city' => '[Klient] Miasto',
            'client-street' => '[Klient] Ulica',
            'client-flat' => '[Klient] Budynek/mieszkanie',
            'property-name' => '[Lokal] Nazwa',
            'property-location' => '[Lokal] Położenie',
            'property-area' => '[Lokal] Powierzchnia',
            'property-rooms' => '[Lokal] Pokoje',
            'property-price' => '[Lokal] Cena',
            'property-price-m2' => '[Lokal] Cena m2',
            'property-additional' => '[Lokal] Przynależne lokale',
            'property-pdf' => '[Lokal] Karta .pdf',
            'investment-name' => '[Inwestycja] Inwestycji',
            'investment-deadline' => '[Inwestycja] Termin zakończenia aktów notarialnych',
            'seller' => '[Sprzedający] Imię i nazwisko'
        ];
    }
}