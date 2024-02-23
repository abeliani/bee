<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

class EnvLoader
{
    public function load(?string $path = null, string $envName = '.env'): void
    {
        if (!$path) {
            $path = ROOT_DIR . DS . $envName;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (!str_starts_with(trim($line), '#')) {
                [$name, $value] = explode('=', $line, 2);
                putenv(sprintf('%s=%s', trim($name), trim($value)));
            }
        }
    }
}