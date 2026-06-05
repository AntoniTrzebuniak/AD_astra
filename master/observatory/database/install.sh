#!/bin/bash
set -e
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-}"
DIR="$(cd "$(dirname "$0")" && pwd)"

if [ -n "$DB_PASS" ]; then
    MYSQL_CMD="mysql -h $DB_HOST -u $DB_USER -p$DB_PASS"
else
    MYSQL_CMD="mysql -h $DB_HOST -u $DB_USER"
fi

echo "Instalacja schematu bazy danych..."
$MYSQL_CMD < "$DIR/schema.sql"
echo "Ładowanie danych testowych..."
$MYSQL_CMD < "$DIR/seed.sql"
echo "Gotowe! Baza ad_astra_observatory jest skonfigurowana."
