<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    private static string $basePath = '';

    public static function setBasePath(string $path): void
    {
        self::$basePath = rtrim($path, '/');
    }

    public static function render(string $template, array $data = [], string $layout = 'layout'): string
    {
        $templatePath = self::$basePath . '/' . $template . '.php';
        if (!is_file($templatePath)) {
            throw new \RuntimeException("View not found: {$templatePath}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $templatePath;
        $content = ob_get_clean();

        if ($layout === '') {
            return $content;
        }

        $layoutPath = self::$basePath . '/' . $layout . '.php';
        if (!is_file($layoutPath)) {
            throw new \RuntimeException("Layout not found: {$layoutPath}");
        }

        ob_start();
        require $layoutPath;
        return ob_get_clean();
    }
}
