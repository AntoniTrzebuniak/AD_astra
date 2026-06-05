<?php

declare(strict_types=1);

namespace App\Helpers;

class View
{
    public static function render(string $template, array $data = []): void
    {
        extract($data);
        $templatePath = OBSERVATORY_ROOT . '/src/Views/' . $template . '.php';
        if (!file_exists($templatePath)) {
            http_response_code(500);
            echo 'View not found: ' . e($template);
            return;
        }
        require OBSERVATORY_ROOT . '/partials/header.php';
        require $templatePath;
        require OBSERVATORY_ROOT . '/partials/footer.php';
    }

    public static function renderPartial(string $template, array $data = []): void
    {
        extract($data);
        require OBSERVATORY_ROOT . '/src/Views/' . $template . '.php';
    }
}
