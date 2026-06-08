# Ad Astra — Centrum Planowania Obserwacji

Strona internetowa dla astrofotografów z modułem PHP+MySQL do koordynacji obserwacji zakryć planetoid.

## Struktura projektu

- `master/public/` — statyczna strona HTML (artykuły, forum, galeria)
- `master/server.js` — serwer Node.js dla forum (port 3000)
- `master/observatory/` — moduł PHP: Centrum Planowania Obserwacji

## Wymagania

- PHP 8.1+ z rozszerzeniami: `pdo_mysql`, `fileinfo`
- MySQL 8.0 / MariaDB 10.5+
- Apache z `mod_rewrite` (lub Docker)
- Node.js 18+ (opcjonalnie, dla forum)

## Uruchomienie — Docker (zalecane)

```bash
docker compose up -d
```

| Usługa | URL |
|--------|-----|
| Centrum Obserwacji (PHP) | http://localhost:8080 |
| Forum (Node.js) | http://localhost:3000 |
| MySQL | localhost:3306 (root/root) |

### Polskie znaki (UTF-8)

Jeśli w bazie widać „NajwiÄ™kszy” zamiast „Największy”, zresetuj wolumen MySQL i załaduj dane ponownie:

```bash
docker compose down -v
docker compose up -d
```

Alternatywnie (bez kasowania całej bazy):

```bash
docker compose exec -T mysql mysql -uroot -proot < master/observatory/database/fix-encoding.sql
docker compose restart php
```


1. Skopiuj folder `master` do `htdocs/ad_astra/`
2. Uruchom MySQL w XAMPP
3. Zaimportuj bazy danych:
   ```bash
   mysql -u root < master/observatory/database/schema.sql
   mysql -u root < master/observatory/database/seed.sql
   ```
4. Skonfiguruj Apache — DocumentRoot lub alias do `master/observatory/`
5. Upewnij się, że `mod_rewrite` jest włączony
6. Otwórz: `http://localhost/ad_astra/observatory/`

### Zmienne środowiskowe bazy danych

| Zmienna | Domyślnie |
|---------|-----------|
| `DB_HOST` | `127.0.0.1` |
| `DB_PORT` | `3306` |
| `DB_NAME` | `ad_astra_observatory` |
| `DB_USER` | `root` |
| `DB_PASS` | `` |

## Konta testowe

| Email | Hasło | Rola |
|-------|-------|------|
| `admin@adastra.pl` | `password` | Administrator |
| `bieszczady@obs.pl` | `password` | Właściciel stacji |

## Funkcjonalności modułu

- Rejestracja i logowanie właścicieli stacji obserwacyjnych
- Katalog planetoid i zdarzeń zakrycia gwiazd
- Planowanie obserwacji na bieżącą noc z wykrywaniem konfliktów
- Mapa stacji (Leaflet.js) z planowanymi planetoidami
- Rejestracja wyników obserwacji i upload zdjęć
- Widoki wg stacji i wg obiektu (historia + galeria)
- Sprinty obserwacyjne: tworzenie, zapisy, archiwum, podsumowanie
- Panel administratora do zarządzania obiektami i zdarzeniami
- Eksport planu nocy do CSV

## Forum Node.js

```bash
cd master
node server.js
# http://localhost:3000
```
