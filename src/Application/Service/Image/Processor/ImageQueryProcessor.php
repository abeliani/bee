<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\Builder\ImageQueryBuilder;
use Abeliani\Blog\Application\Service\Image\Processor\Handler\ImageHandlerInterface;
use Abeliani\Blog\Application\Service\Image\Processor\Handler\StreamHandler;
use Abeliani\Blog\Application\Service\Image\TypeEnum;

class ImageQueryProcessor
{
    public function __construct(
        private ImageQueryBuilder $builder,
        private readonly string $preferredLibrary,
        private readonly string $secondaryLibrary,
    ) {
        if ($this->secondaryLibrary === $this->preferredLibrary) {
            throw new \InvalidArgumentException('The preferred library cannot be the same as the secondary library');
        }
    }

    /**
     * @throws \ImagickException
     * @throws \Exception
     */
    public function process(
        ?ProcessorContext $context = null,
        ?ImageHandlerInterface $stream = null,
        ?ImageQueryBuilder $builder = null,
    ): void {
        if ($builder === null && $this->builder) {
            $builder = $this->builder;
        }
        if ($builder === null) {
            throw new \RuntimeException('The query builder must be set');
        }
        if (!($stream = $stream->getStream())) {
            throw new \RuntimeException('The image stream cannot be empty');
        }

        foreach ($builder as $type => $builderActions) {
            while ($action = array_shift($builderActions)) {
                if (is_callable($action)) {
                    $action = $action($context);
                }
                if ($type === TypeEnum::branches->name) {
                    $branchStream = new StreamHandler($stream);
                    $this->freezeStream($stream, $action);
                    $this->process($context, $branchStream, $action);
                    $stream = $this->defrostStream($action);
                    continue;
                }

                $processor = $this->fetchActionProcessor($action, $library);
                LibControl::make($stream, $image, $library);
                $image = $processor($image, $action);
                LibControl::reset($stream);
            }
        }

        LibControl::clean($stream, $image ?? null);
    }

    private function freezeStream(mixed $stream, ImageQueryBuilder $builder): void
    {
        $tmpFileName = sprintf(
            '%s%simage_builder_%s',
            sys_get_temp_dir(),
            DIRECTORY_SEPARATOR,
            $builder->getId()
        );

        $newStream = fopen($tmpFileName, 'w+');

        if (!stream_copy_to_stream($stream, $newStream)) {
            throw new \RuntimeException('Failed to freeze a stream');
        }

        fclose($stream) and fclose($newStream);
    }

    /**
     * @return resource
     * @throws \Exception
     */
    private function defrostStream(ImageQueryBuilder $builder): mixed
    {
        $freezeFile = sprintf(
            '%s%simage_builder_%s',
            sys_get_temp_dir(),
            DIRECTORY_SEPARATOR,
            $builder->getId()
        );

        $freezeStream = fopen($freezeFile, 'r+');

        if (!$defrostStream = tmpfile()) {
            throw new \Exception('Failed to create a tmp file');
        }

        if (!stream_copy_to_stream($freezeStream, $defrostStream) || !rewind($defrostStream)) {
            throw new \RuntimeException('Failed to defrost stream');
        }

        fclose($freezeStream) and unlink($freezeFile);

        return $defrostStream;
    }

    private function fetchActionProcessor(BuilderActionInterface $action, ?string &$library): ProcessorInterface
    {
        if (empty($attributes = (new \ReflectionClass($action))->getAttributes())) {
            throw new \RuntimeException("Not found `{BuilderActionInterface::getName()}` processor");
        }
        // get preferred library to process action
        if (!$action->getLibrary()) {
            usort($attributes, function(\ReflectionAttribute $l, \ReflectionAttribute $r) {
                $postLeft = str_ends_with($l->getName(), $this->preferredLibrary) ? 0
                    : (str_ends_with($l->getName(), $this->secondaryLibrary) ? 1 : 2);
                $postRight = str_ends_with($r->getName(), $this->preferredLibrary) ? 0
                    : (str_ends_with($r->getName(), $this->secondaryLibrary) ? 1 : 2);

                return $postLeft - $postRight;
            });

            if (count($attributes) === 1 && !str_ends_with($attributes[0]->getName(), $this->preferredLibrary)) {
                throw new \RuntimeException("Fail to fetch preferred library for `{BuilderActionInterface::getName()}` processor");
            }
            $library = $this->preferredLibrary;
            // preferred library will be on top of the array
            array_pop($attributes);
        } else {
            $library = $action->getLibrary();
            $attributes = array_filter($attributes, fn(\ReflectionAttribute $attr) =>
                str_ends_with($attr->getName(), $action->getLibrary())
            );
        }

        if (count($attributes) !== 1) {
            throw new \RuntimeException("Error fetching the `{BuilderActionInterface::getName()}` processor");
        }

        return array_shift($attributes)->newInstance();
    }
}
