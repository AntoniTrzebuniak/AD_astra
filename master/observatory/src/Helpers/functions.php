<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function baseUrl(string $path = ''): string
{
    $script = dirname($_SERVER['SCRIPT_NAME'] ?? '/observatory/index.php');
    $base = rtrim(str_replace('\\', '/', $script), '/');
    if ($path === '') {
        return $base . '/';
    }
    return $base . '/' . ltrim($path, '/');
}

function assetUrl(string $path): string
{
    return baseUrl('public/' . ltrim($path, '/'));
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $value;
}

function getTonightDate(): string
{
    $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Warsaw'));
    $hour = (int) $now->format('H');
    if ($hour < 12) {
        return $now->modify('-1 day')->format('Y-m-d');
    }
    return $now->format('Y-m-d');
}

function formatUtcLocal(string $utcDatetime): string
{
    $utc = new DateTimeImmutable($utcDatetime, new DateTimeZone('UTC'));
    $local = $utc->setTimezone(new DateTimeZone('Europe/Warsaw'));
    return $local->format('d.m.Y H:i:s') . ' CET (UTC: ' . $utc->format('H:i:s') . ')';
}

function resultLabel(string $result): string
{
    return match ($result) {
        'positive' => 'Pozytywna',
        'negative' => 'Negatywna',
        'clouded' => 'Zachmurzenie',
        default => 'Brak danych',
    };
}

function statusLabel(string $status): string
{
    return match ($status) {
        'planned' => 'Zaplanowana',
        'confirmed' => 'Potwierdzona',
        'completed' => 'Zakończona',
        'cancelled' => 'Anulowana',
        'upcoming' => 'Nadchodząca',
        'active' => 'Aktywna',
        default => $status,
    };
}

function toMysqlDatetime(string $input): string
{
    $normalized = str_replace('T', ' ', trim($input));
    if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $normalized)) {
        return $normalized . ':00';
    }
    return $normalized;
}
