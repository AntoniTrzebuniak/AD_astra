SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET CHARACTER SET utf8mb4;

USE ad_astra_observatory;

INSERT INTO users (email, password_hash, name, role) VALUES
('admin@adastra.pl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin'),
('bieszczady@obs.pl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jan Kowalski', 'owner'),
('poznan@obs.pl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Anna Nowak', 'owner'),
('torun@obs.pl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Piotr Wiśniewski', 'owner'),
('krakow@obs.pl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria Zielińska', 'owner'),
('gdansk@obs.pl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tomasz Lewandowski', 'owner');

INSERT INTO stations (user_id, name, latitude, longitude, altitude_m, equipment, description) VALUES
(2, 'Stacja Bieszczady', 49.2500, 22.5000, 850, 'Meade LX200 12", SBIG STF-8300, VTI GPS', 'Stacja w Bieszczadach, niskie zanieczyszczenie świetlne'),
(3, 'Obserwatorium Poznań', 52.4064, 16.9252, 95, 'Celestron C11, ZWO ASI294MC Pro', 'Miejska stacja obserwacyjna przy Wydziale Fizyki'),
(4, 'Stacja Toruń', 53.0138, 18.5984, 45, 'Sky-Watcher 250P, QHY174M', 'Stacja współpracująca z UMK'),
(5, 'Obserwatorium Kraków', 50.0647, 19.9450, 220, 'Takahashi FSQ-106, Moravian G4-16000', 'Stacja na wzgórzu nad Krakowem'),
(6, 'Stacja Gdańsk', 54.3520, 18.6466, 15, 'Celestron EdgeHD 8", ASI178MM', 'Stacja nadmorska z mobilnym montażem');

INSERT INTO celestial_objects (designation, name, type, magnitude, diameter_km, notes) VALUES
('(99942) Apophis', 'Apophis', 'asteroid', 19.70, 0.370, 'Potencjalnie niebezpieczna planetoida, częste zakrycia'),
('(433) Eros', 'Eros', 'asteroid', 11.16, 16.840, 'Pierwsza planetoida zbliżona przez sondę kosmiczną'),
('(4) Vesta', 'Westa', 'asteroid', 5.10, 525.400, 'Jedna z największych planetoid pasa głównego'),
('(1) Ceres', 'Ceres', 'asteroid', 3.34, 939.400, 'Największy obiekt pasa głównego'),
('(257) Silesia', 'Silesia', 'asteroid', 12.50, 0.063, 'Mała planetoida, częste zakrycia gwiazd'),
('(386) Siegena', 'Siegena', 'asteroid', 11.80, 0.150, 'Regularne zdarzenia zakrycia'),
('(41) Daphne', 'Daphne', 'asteroid', 10.90, 0.190, 'Nieregularny kształt'),
('(61788) 2000 QP181', '2000 QP181', 'asteroid', 15.20, 0.080, 'Obiekt transneptunowy pasa głównego'),
('(425) Cornelia', 'Cornelia', 'asteroid', 12.10, 0.120, 'Planetoida typu S'),
('(15112) Arlenewolfe', 'Arlenewolfe', 'asteroid', 14.50, 0.050, 'Mała planetoida do obserwacji zakryć');

INSERT INTO occultation_events (object_id, star_designation, star_magnitude, predicted_time_utc, duration_ms, path_uncertainty_km, magnitude_drop, status) VALUES
(1, 'TYC 1234-567-1', 10.20, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 2 HOUR), 3200, 15.00, 3.50, 'upcoming'),
(5, 'UCAC4 512-012345', 11.50, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 4 HOUR), 2800, 8.00, 2.80, 'upcoming'),
(6, 'TYC 2345-678-2', 9.80, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 6 HOUR), 4100, 12.00, 3.10, 'upcoming'),
(2, 'HIP 54321', 8.90, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 26 HOUR), 3500, 20.00, 4.20, 'upcoming'),
(7, 'TYC 3456-789-3', 10.50, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 30 HOUR), 2600, 10.00, 2.50, 'upcoming'),
(3, 'UCAC4 789-045678', 9.20, DATE_SUB(UTC_TIMESTAMP(), INTERVAL 48 HOUR), 5000, 25.00, 5.00, 'completed');

INSERT INTO observation_plans (station_id, event_id, night_date, planned_start_utc, planned_end_utc, status, notes) VALUES
(1, 1, CURDATE(), DATE_ADD(UTC_TIMESTAMP(), INTERVAL 1 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL 3 HOUR), 'confirmed', 'Priorytet: Apophis'),
(2, 2, CURDATE(), DATE_ADD(UTC_TIMESTAMP(), INTERVAL 3 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL 5 HOUR), 'planned', 'Silesia - slot 2'),
(3, 3, CURDATE(), DATE_ADD(UTC_TIMESTAMP(), INTERVAL 5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL 7 HOUR), 'planned', 'Siegena - slot 3'),
(4, 4, DATE_ADD(CURDATE(), INTERVAL 1 DAY), DATE_ADD(UTC_TIMESTAMP(), INTERVAL 25 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL 27 HOUR), 'planned', 'Eros jutro'),
(5, 5, DATE_ADD(CURDATE(), INTERVAL 1 DAY), DATE_ADD(UTC_TIMESTAMP(), INTERVAL 29 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL 31 HOUR), 'planned', 'Daphne jutro');

INSERT INTO observations (station_id, object_id, plan_id, observed_at_utc, result, duration_ms, notes) VALUES
(1, 3, NULL, DATE_SUB(UTC_TIMESTAMP(), INTERVAL 48 HOUR), 'positive', 4800, 'Wyraźne zakrycie, dobry SNR'),
(2, 5, NULL, DATE_SUB(UTC_TIMESTAMP(), INTERVAL 72 HOUR), 'negative', 3000, 'Brak zakrycia - poza ścieżką cienia'),
(3, 6, NULL, DATE_SUB(UTC_TIMESTAMP(), INTERVAL 96 HOUR), 'clouded', NULL, 'Całkowite zachmurzenie'),
(4, 2, NULL, DATE_SUB(UTC_TIMESTAMP(), INTERVAL 120 HOUR), 'positive', 3400, 'Krótkie ale wyraźne zakrycie Eros');

INSERT INTO sprints (creator_id, target_object_id, title, description, start_utc, end_utc, status) VALUES
(1, 1, 'Sprint Apophis 2026', 'Masowa obserwacja zakrycia Apophis - jak najwięcej stacji!', DATE_ADD(UTC_TIMESTAMP(), INTERVAL 1 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL 5 HOUR), 'upcoming'),
(1, 5, 'Sprint Silesia - marzec', 'Koordynowana obserwacja (257) Silesia', DATE_SUB(UTC_TIMESTAMP(), INTERVAL 30 DAY), DATE_SUB(UTC_TIMESTAMP(), INTERVAL 29 DAY), 'completed');

INSERT INTO sprint_registrations (sprint_id, station_id) VALUES
(1, 1), (1, 2), (1, 3),
(2, 1), (2, 2), (2, 4), (2, 5);
