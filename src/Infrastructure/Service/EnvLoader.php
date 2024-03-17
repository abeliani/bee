<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

class EnvLoader
{
    public function load(?string $path = null, string $envName = '.env'): void
    {
        $path ??= ROOT_DIR . DS . $envName;

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }
            [$name, $value] = explode('=', $line, 2);
            putenv(sprintf('%s=%s', trim($name), trim($value)));
        }
    }

    public static function get(string $name): string|array|false
    {
        return getenv($name, true);
    }
}
