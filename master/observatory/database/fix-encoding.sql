-- Naprawa kodowania polskich znaków w istniejącej bazie (uruchom po zmianie charset)
USE ad_astra_observatory;

UPDATE celestial_objects SET
    notes = 'Największy obiekt pasa głównego' WHERE designation = '(1) Ceres';
UPDATE celestial_objects SET
    notes = 'Jedna z największych planetoid pasa głównego' WHERE designation = '(4) Vesta';
UPDATE celestial_objects SET
    notes = 'Pierwsza planetoida zbliżona przez sondę kosmiczną' WHERE designation = '(433) Eros';
UPDATE celestial_objects SET
    notes = 'Mała planetoida, częste zakrycia gwiazd' WHERE designation = '(257) Silesia';
UPDATE celestial_objects SET
    notes = 'Nieregularny kształt' WHERE designation = '(41) Daphne';
UPDATE celestial_objects SET
    notes = 'Mała planetoida do obserwacji zakryć' WHERE designation = '(15112) Arlenewolfe';

UPDATE stations SET
    description = 'Stacja w Bieszczadach, niskie zanieczyszczenie świetlne' WHERE name = 'Stacja Bieszczady';
UPDATE stations SET
    name = 'Obserwatorium Poznań', description = 'Miejska stacja obserwacyjna przy Wydziale Fizyki' WHERE name LIKE 'Obserwatorium Pozna%';
UPDATE stations SET
    name = 'Stacja Toruń', description = 'Stacja współpracująca z UMK' WHERE name LIKE 'Stacja Toru%';
UPDATE stations SET
    name = 'Obserwatorium Kraków', description = 'Stacja na wzgórzu nad Krakowem' WHERE name LIKE 'Obserwatorium Krak%';
UPDATE stations SET
    name = 'Stacja Gdańsk', description = 'Stacja nadmorska z mobilnym montażem' WHERE name LIKE 'Stacja Gda%';

UPDATE users SET name = 'Piotr Wiśniewski' WHERE email = 'torun@obs.pl';
UPDATE users SET name = 'Maria Zielińska' WHERE email = 'krakow@obs.pl';
