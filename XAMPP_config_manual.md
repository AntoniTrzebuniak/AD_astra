Opcja A — Docker (jeśli chcesz używać docker compose)
1. Zainstaluj i uruchom Docker Desktop
Pobierz: https://www.docker.com/products/docker-desktop/
Zainstaluj i uruchom Docker Desktop
Poczekaj, aż w trayu status będzie Running (zielona ikona)
2. Sprawdź, czy Docker działa
W PowerShell lub CMD:

docker version
docker compose version
Oba polecenia powinny zwrócić dane bez błędu.

3. Uruchom projekt
W katalogu projektu (tam, gdzie jest docker-compose.yml):

cd C:\ścieżka\do\AD_astra
docker compose up -d
4. Otwórz w przeglądarce
Centrum Obserwacji (PHP): http://localhost:8080
Forum (Node.js): http://localhost:3000
5. Zatrzymanie
docker compose down
Opcja B — XAMPP (najprostsze na studiach, bez Dockera)
Jeśli Docker sprawia problemy, na Windowsie zwykle łatwiej jest XAMPP.

1. Instalacja
Pobierz XAMPP: https://www.apachefriends.org/
Zainstaluj (Apache + MySQL)
W XAMPP Control Panel uruchom Apache i MySQL
2. Skopiuj projekt
Skopiuj folder master do:

C:\xampp\htdocs\ad_astra\master\
Struktura:

C:\xampp\htdocs\ad_astra\master\
├── observatory\      ← moduł PHP
├── public\           ← strona HTML
└── server.js         ← forum Node
3. Baza danych
Otwórz http://localhost/phpmyadmin
Zakładka Import
Zaimportuj po kolei:
master/observatory/database/schema.sql
master/observatory/database/seed.sql
Albo w terminalu (jeśli mysql jest w PATH):

cd C:\xampp\htdocs\ad_astra
C:\xampp\mysql\bin\mysql.exe -u root < master\observatory\database\schema.sql
C:\xampp\mysql\bin\mysql.exe -u root < master\observatory\database\seed.sql
4. Włącz mod_rewrite w Apache
W C:\xampp\apache\conf\httpd.conf odkomentuj:

LoadModule rewrite_module modules/mod_rewrite.so
oraz ustaw AllowOverride All dla htdocs (szukaj sekcji <Directory "C:/xampp/htdocs">).

Zrestartuj Apache w XAMPP.

5. Adresy
Centrum Obserwacji: http://localhost/ad_astra/master/observatory/
Strona główna: http://localhost/ad_astra/master/public/index.html
6. Forum Node.js (opcjonalnie)
W osobnym terminalu:

cd C:\xampp\htdocs\ad_astra\master
npm install
node server.js
Forum: http://localhost:3000

7. Logowanie testowe
Email	Hasło
admin@adastra.pl
password
bieszczady@obs.pl
password
Szybka diagnoza Dockera
Polecenie	Oczekiwany wynik
docker version
Client i Server — bez błędu
docker info
Informacje o systemie — bez błędu pipe
Jeśli docker version pokazuje tylko Client, a Server ma błąd — Docker Desktop nie jest uruchomiony.