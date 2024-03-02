<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Handler;

use Psr\Http\Message\UploadedFileInterface;

readonly class UploadedFileHandler implements ImageHandlerInterface
{
    public function __construct(private UploadedFileInterface $file)
    {
    }

    /**
     * @throws \Exception
     */
    public function getStream(): mixed
    {
        if (!$newStream = tmpfile()) {
            throw new \Exception('Failed to create a tmp file');
        }

        if (!fwrite($newStream, $this->file->getStream()->getContents()) || !rewind($newStream)) {
            throw new \RuntimeException('Failed to create stream');
        }

        return $newStream;
    }
}
