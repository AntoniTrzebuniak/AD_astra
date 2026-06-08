-- Naprawa kodowania UTF-8 w istniejącej bazie Docker/XAMPP
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

USE ad_astra_observatory;

ALTER DATABASE ad_astra_observatory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE stations CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE celestial_objects CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE occultation_events CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE observation_plans CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE observations CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE photos CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE sprints CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE sprint_registrations CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

UPDATE users SET name = 'Piotr Wiśniewski' WHERE email = 'torun@obs.pl';
UPDATE users SET name = 'Maria Zielińska' WHERE email = 'krakow@obs.pl';

UPDATE stations SET
    name = 'Obserwatorium Poznań',
    description = 'Miejska stacja obserwacyjna przy Wydziale Fizyki'
WHERE user_id = 3;
UPDATE stations SET
    name = 'Stacja Toruń',
    description = 'Stacja współpracująca z UMK'
WHERE user_id = 4;
UPDATE stations SET
    name = 'Obserwatorium Kraków',
    description = 'Stacja na wzgórzu nad Krakowem'
WHERE user_id = 5;
UPDATE stations SET
    name = 'Stacja Gdańsk',
    description = 'Stacja nadmorska z mobilnym montażem'
WHERE user_id = 6;
UPDATE stations SET
    description = 'Stacja w Bieszczadach, niskie zanieczyszczenie świetlne'
WHERE user_id = 2;

UPDATE celestial_objects SET name = 'Westa', notes = 'Jedna z największych planetoid pasa głównego' WHERE designation = '(4) Vesta';
UPDATE celestial_objects SET notes = 'Największy obiekt pasa głównego' WHERE designation = '(1) Ceres';
UPDATE celestial_objects SET notes = 'Potencjalnie niebezpieczna planetoida, częste zakrycia' WHERE designation = '(99942) Apophis';
UPDATE celestial_objects SET notes = 'Pierwsza planetoida zbliżona przez sondę kosmiczną' WHERE designation = '(433) Eros';
UPDATE celestial_objects SET notes = 'Mała planetoida, częste zakrycia gwiazd' WHERE designation = '(257) Silesia';
UPDATE celestial_objects SET notes = 'Regularne zdarzenia zakrycia' WHERE designation = '(386) Siegena';
UPDATE celestial_objects SET notes = 'Nieregularny kształt' WHERE designation = '(41) Daphne';
UPDATE celestial_objects SET notes = 'Obiekt transneptunowy pasa głównego' WHERE designation = '(61788) 2000 QP181';
UPDATE celestial_objects SET notes = 'Planetoida typu S' WHERE designation = '(425) Cornelia';
UPDATE celestial_objects SET notes = 'Mała planetoida do obserwacji zakryć' WHERE designation = '(15112) Arlenewolfe';

UPDATE observations SET notes = 'Wyraźne zakrycie, dobry SNR' WHERE result = 'positive' AND duration_ms = 4800;
UPDATE observations SET notes = 'Brak zakrycia - poza ścieżką cienia' WHERE result = 'negative' AND duration_ms = 3000;
UPDATE observations SET notes = 'Całkowite zachmurzenie' WHERE result = 'clouded';
UPDATE observations SET notes = 'Krótkie ale wyraźne zakrycie Eros' WHERE result = 'positive' AND duration_ms = 3400;

UPDATE sprints SET
    title = 'Sprint Apophis 2026',
    description = 'Masowa obserwacja zakrycia Apophis - jak najwięcej stacji!'
WHERE target_object_id = 1 AND status = 'upcoming';
UPDATE sprints SET
    title = 'Sprint Silesia - marzec',
    description = 'Koordynowana obserwacja (257) Silesia'
WHERE target_object_id = 5 AND status = 'completed';
