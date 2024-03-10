<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor;

class LibControl
{
    /**
     * @param string $lib
     * @param \Imagick|\GdImage|null $image
     * @param resource $stream
     * @throws \ImagickException
     * @throws \Exception
     */
    public static function make(mixed $stream, mixed &$image, string $lib): void
    {
        if ($image) {
            if ($lib === \GdImage::class && $image instanceof \Imagick) {
                $image->writeImageFile($stream);
                $image->destroy();
                $image = null;
            }

            if ($lib === \Imagick::class && $image instanceof \GdImage) {
                imagewebp($image, $stream, 100);
                imagedestroy($image);
                $image = null;
            }

            if ($image === null) {
                rewind($stream);
            }
        }

        if ($image === null) {
            switch ($lib) {
                case \Imagick::class:
                    $image = new \Imagick();
                    $image->readImageFile($stream);
                    break;
                case \GdImage::class:
                    if (!$image = imagecreatefromstring(stream_get_contents($stream))) {
                        throw new \Exception('Fail to create image');
                    }
                    break;
                default:
                    throw new \LogicException('Unsupported library type');
            }
        }

        rewind($stream);
    }

    /**
     * @param \Imagick|\GdImage|null $image
     * @return bool
     */
    public static function clean(mixed $stream, mixed $image): bool
    {
        if (!fclose($stream) || !$image) {
            throw new \RuntimeException('Failed to clean image builder');
        }

        if ($image instanceof \GdImage) {
            return imagedestroy($image);
        }

        return $image->clear() and $image->destroy();
    }
}