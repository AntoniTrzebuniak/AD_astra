<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? SITE_NAME) ?> - Ad Astra</title>
    <link rel="stylesheet" href="<?= e(assetUrl('styles.css')) ?>">
    <link rel="stylesheet" href="<?= e(assetUrl('css/observatory.css')) ?>">
    <style>
        main.observatory-page .data-table a,
        main.observatory-page .data-table a:link,
        main.observatory-page .data-table a:visited,
        main.observatory-page .panel a:not(.btn):not(.btn-secondary):not(.photo-thumb),
        main.observatory-page .card h3 a,
        main.observatory-page .card h3 a:link,
        main.observatory-page .card h3 a:visited {
            color: #FFD700 !important;
            text-decoration: none !important;
        }
        main.observatory-page .data-table a:hover,
        main.observatory-page .panel a:not(.btn):not(.btn-secondary):not(.photo-thumb):hover,
        main.observatory-page .card h3 a:hover {
            color: #fff !important;
            text-decoration: underline !important;
            text-decoration-color: #FFD700 !important;
        }
        main.observatory-page a.btn,
        main.observatory-page a.btn:link,
        main.observatory-page a.btn:visited,
        main.observatory-page a.btn:hover {
            color: #000 !important;
            text-decoration: none !important;
        }
    </style>
    <script src="<?= e(assetUrl('js/countdown.js')) ?>" defer></script>
</head>
<body>
    <header class="main-header">
        <h1>Ad Astra</h1>
        <nav class="main-menu">
            <ul>
                <li><a href="/site/index.html">Strona Główna</a></li>
                <li><a href="/site/articles.html">Artykuły</a></li>
                <li><a href="/site/forum.html">Forum</a></li>
                <li><a href="/site/Oferta.html">Oferta i Kontakt</a></li>
                <li><a href="/site/Astrofotografia.html">Astrofotografia</a></li>
                <li><a href="<?= e(baseUrl()) ?>" class="active-nav">Centrum Obserwacji</a></li>
            </ul>
        </nav>
    </header>

    <main class="observatory-page">
        <nav class="obs-nav">
            <a href="<?= e(baseUrl()) ?>">Pulpit</a>
            <a href="<?= e(baseUrl('map')) ?>">Mapa</a>
            <a href="<?= e(baseUrl('stations')) ?>">Stacje</a>
            <a href="<?= e(baseUrl('objects')) ?>">Obiekty</a>
            <a href="<?= e(baseUrl('sprints')) ?>">Sprinty</a>
            <?php if (\App\Helpers\Auth::check()): ?>
                <a href="<?= e(baseUrl('plan/tonight')) ?>">Plan na dziś</a>
                <a href="<?= e(baseUrl('observations/new')) ?>">Nowa obserwacja</a>
                <a href="<?= e(baseUrl('station/edit')) ?>">Moja stacja</a>
                <?php if (\App\Helpers\Auth::isAdmin()): ?>
                    <a href="<?= e(baseUrl('admin')) ?>">Admin</a>
                <?php endif; ?>
                <a href="<?= e(baseUrl('logout')) ?>">Wyloguj (<?= e($_SESSION['user_name'] ?? '') ?>)</a>
            <?php else: ?>
                <a href="<?= e(baseUrl('login')) ?>">Logowanie</a>
                <a href="<?= e(baseUrl('register')) ?>">Rejestracja</a>
            <?php endif; ?>
        </nav>

        <?php if ($msg = flash('success')): ?>
            <div class="alert alert-success"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
            <div class="alert alert-error"><?= e($msg) ?></div>
        <?php endif; ?>
