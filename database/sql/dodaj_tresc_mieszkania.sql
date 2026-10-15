-- Opis / atuty mieszkania (pole "Treść" w panelu, sekcja na stronie mieszkania).
-- Odpowiednik migracji 2026_10_15_190000_add_content_to_properties_table.
ALTER TABLE `properties` ADD `content` TEXT NULL DEFAULT NULL AFTER `meta_description`;
