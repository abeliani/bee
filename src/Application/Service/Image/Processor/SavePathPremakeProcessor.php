<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor;

use Abeliani\Blog\Application\Service\Image\Builder\ImageQueryBuilder;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Save;
use Abeliani\Blog\Application\Service\Image\ManipulateEnum;
use Abeliani\Blog\Application\Service\Image\TypeEnum;

class SavePathPremakeProcessor
{
    private array $result = [];

    public function __construct(private readonly ImageQueryBuilder $builder)
    {
    }

    public function process(?ImageQueryBuilder $builder = null): array
    {
        if ($builder === null) {
            $builder = $this->builder;
        }

        foreach ($builder as $type => $builderActions) {
            while ($action = array_shift($builderActions)) {
                if ($type === TypeEnum::branches->name) {
                    $this->result += $this->process($action);
                    continue;
                }
                if ($type !== TypeEnum::manipulate->name) {
                    continue;
                }
                /** @var $action Save */
                if ($action::getName() === ManipulateEnum::save->name) {
                    $filePath = $action->getFilePath();
                    $this->result[$builder->getLabel()] = $filePath;

                    if (is_dir($path = dirname($filePath))) {
                        continue;
                    }
                    if (!mkdir($path, 0755, true)) {
                        throw new \RuntimeException('Fail to create directories');
                    }
                }
            }
        }

        return $this->result;
    }
}
