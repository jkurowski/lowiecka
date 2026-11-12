-- Raport cen dla dane.gov.pl (rozporządzenie MC, Dz.U. 2026 poz. 1489): współrzędne lokalizacji inwestycji
-- Pola D8 (długość) i D9 (szerokość geograficzna), WGS-84, sześć miejsc po przecinku.
-- Uruchomić raz na bazie serwera (i lokalnie) PRZED wgraniem kodu - formularz inwestycji zapisuje te pola.
-- Bez wartości raport działa, ale wpisuje w oba pola "X".

ALTER TABLE `investments`
    ADD COLUMN `inv_longitude` DECIMAL(9,6) NULL DEFAULT NULL AFTER `inv_postal_code`,
    ADD COLUMN `inv_latitude` DECIMAL(9,6) NULL DEFAULT NULL AFTER `inv_longitude`;
