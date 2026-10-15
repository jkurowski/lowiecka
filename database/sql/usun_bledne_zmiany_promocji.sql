-- Usuwa z historii cen (property_prices) błędne pary wpisów powstałe przez
-- natychmiastowe wyłączanie promocji (EndPropertyPromotion na kolejce sync):
-- wpis "promocja włączona" (promotion = 1) i w ciągu kilku sekund wpis
-- odwracający go (promotion = 0, ceny zamienione miejscami).

-- 1. Podgląd - wpisy, które zostaną usunięte
SELECT on_.id AS id_promocja_wl, off_.id AS id_promocja_wyl, on_.property_id,
       on_.price_brutto, on_.new_price_brutto, on_.changed_at
FROM property_prices on_
JOIN property_prices off_
  ON off_.property_id = on_.property_id
 AND on_.promotion = 1
 AND off_.promotion = 0
 AND off_.price_brutto = on_.new_price_brutto
 AND off_.new_price_brutto = on_.price_brutto
 AND off_.id > on_.id
 AND TIMESTAMPDIFF(SECOND, on_.changed_at, off_.changed_at) BETWEEN 0 AND 5;

-- 2. Usunięcie
DELETE on_, off_
FROM property_prices on_
JOIN property_prices off_
  ON off_.property_id = on_.property_id
 AND on_.promotion = 1
 AND off_.promotion = 0
 AND off_.price_brutto = on_.new_price_brutto
 AND off_.new_price_brutto = on_.price_brutto
 AND off_.id > on_.id
 AND TIMESTAMPDIFF(SECOND, on_.changed_at, off_.changed_at) BETWEEN 0 AND 5;
