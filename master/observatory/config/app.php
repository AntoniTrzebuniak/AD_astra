<?php

declare(strict_types=1);

define('OBSERVATORY_ROOT', dirname(__DIR__));
define('OBSERVATORY_PUBLIC', OBSERVATORY_ROOT . '/public');
define('UPLOAD_DIR', OBSERVATORY_PUBLIC . '/uploads/photos');
define('UPLOAD_URL', 'public/uploads/photos');
define('MAX_UPLOAD_BYTES', 10 * 1024 * 1024);
define('SITE_NAME', 'Ad Astra - Centrum Obserwacji');

date_default_timezone_set('Europe/Warsaw');

ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');

if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
