@echo off
echo Naprawa kodowania UTF-8 w bazie danych...
docker compose exec -T mysql mysql -uroot -proot --default-character-set=utf8mb4 < master/observatory/database/fix-encoding.sql
docker compose restart php
echo Gotowe. Odswiez strone w przegladarce (Ctrl+F5).
