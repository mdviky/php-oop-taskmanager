<?php

declare(strict_types=1);

namespace App\Core;

final class Config
{
    private static array $items = [];

    public static function load(string $path, string $key): void
    {
        if (!is_file($path)) {
            throw new \RuntimeException("Config file not found: {$path}");
        }

        $data = require $path;
        if (!is_array($data)) {
            throw new \RuntimeException("Config file must return an array: {$path}");
        }

        self::$items[$key] = $data;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = self::$items;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}
