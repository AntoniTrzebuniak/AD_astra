<?php

declare(strict_types=1);

require __DIR__ . '/config/app.php';
require __DIR__ . '/config/database.php';
require __DIR__ . '/src/Helpers/functions.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = __DIR__ . '/src/' . $relative . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\MapController;
use App\Controllers\ObjectController;
use App\Controllers\ObservationController;
use App\Controllers\PlanController;
use App\Controllers\SprintController;
use App\Controllers\StationController;

try {
    $db = getDbConnection();
} catch (PDOException $e) {
    http_response_code(503);
    echo '<!DOCTYPE html><html lang="pl"><head><meta charset="UTF-8"><title>Błąd bazy danych</title></head><body>';
    echo '<h1>Nie można połączyć z bazą danych</h1>';
    echo '<p>Uruchom schema.sql i seed.sql, lub skonfiguruj zmienne DB_*.</p>';
    echo '<p><small>' . e($e->getMessage()) . '</small></p></body></html>';
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
$path = $uri;
if ($base !== '' && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base));
}
$path = '/' . trim($path, '/');
if ($path === '/') {
    $path = '';
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$routes = [
    'GET' => [
        '' => [DashboardController::class, 'index'],
        'map' => [MapController::class, 'index'],
        'api/map-data' => [MapController::class, 'apiData'],
        'login' => [AuthController::class, 'loginForm'],
        'register' => [AuthController::class, 'registerForm'],
        'logout' => [AuthController::class, 'logout'],
        'station/edit' => [StationController::class, 'editForm'],
        'stations' => [StationController::class, 'index'],
        'objects' => [ObjectController::class, 'index'],
        'plan/tonight' => [PlanController::class, 'tonight'],
        'plan/export' => [PlanController::class, 'export'],
        'observations/new' => [ObservationController::class, 'createForm'],
        'sprints' => [SprintController::class, 'index'],
        'sprints/create' => [SprintController::class, 'createForm'],
        'admin' => [AdminController::class, 'index'],
    ],
    'POST' => [
        'login' => [AuthController::class, 'login'],
        'register' => [AuthController::class, 'register'],
        'station/edit' => [StationController::class, 'update'],
        'plan/tonight' => [PlanController::class, 'store'],
        'observations/new' => [ObservationController::class, 'store'],
        'sprints/create' => [SprintController::class, 'store'],
        'admin/object' => [AdminController::class, 'createObject'],
        'admin/event' => [AdminController::class, 'createEvent'],
    ],
];

if (preg_match('#^stations/(\d+)$#', $path, $m) && $method === 'GET') {
    (new StationController($db))->show((int) $m[1]);
    exit;
}
if (preg_match('#^objects/(\d+)$#', $path, $m) && $method === 'GET') {
    (new ObjectController($db))->show((int) $m[1]);
    exit;
}
if (preg_match('#^sprints/(\d+)$#', $path, $m) && $method === 'GET') {
    (new SprintController($db))->show((int) $m[1]);
    exit;
}
if (preg_match('#^sprints/(\d+)/register$#', $path, $m) && $method === 'POST') {
    (new SprintController($db))->register((int) $m[1]);
    exit;
}
if (preg_match('#^sprints/(\d+)/photo$#', $path, $m) && $method === 'POST') {
    (new SprintController($db))->uploadPhoto((int) $m[1]);
    exit;
}
if (preg_match('#^sprints/(\d+)/summary$#', $path, $m) && $method === 'GET') {
    (new SprintController($db))->summary((int) $m[1]);
    exit;
}

$route = $routes[$method][$path] ?? null;
if ($route) {
    [$class, $action] = $route;
    (new $class($db))->$action();
    exit;
}

http_response_code(404);
\App\Helpers\View::render('errors/404', ['title' => 'Strona nie znaleziona']);
