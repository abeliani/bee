<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

final readonly class Hydrator
{
    /**
     * @throws \JsonException
     */
    public function hydrate(array $data, object $object): void
    {
        foreach ($data as $key => $value) {
            if (!property_exists($object, $key)) {
                continue;
            }
            $property = new \ReflectionProperty($object, $key);
            $propertyType = $property->getType();
            // object value it is a certain type of a request value like UploadedFile
            if ($propertyType && !$propertyType->isBuiltin() && !is_object($value)) {
                if (is_string($value) && str_starts_with($value, '{')) {
                    $value = json_decode($value, true, 10, JSON_THROW_ON_ERROR);
                }
                $typeName = $propertyType->getName();
                if (enum_exists($typeName)) {
                    $reflectionEnum = new \ReflectionEnum($typeName);

                    if ($reflectionEnum->isBacked()) {
                        $enum = call_user_func_array([$typeName, 'tryFrom'], [
                            $reflectionEnum->getBackingType()->getName() === 'int' ? (int) $value : $value
                        ]);
                        $property->setValue($object, $enum);
                    }
                    continue;
                }
                if (class_exists($typeName)) {
                    if ($value === '') { // empty value
                        $value = [];
                    }
                    $nestedObject = new $typeName;
                    $this->hydrate($value, $nestedObject);
                    $property->setValue($object, $nestedObject);
                }
            } else {
                $value = $value === '' && $propertyType->allowsNull() ? null : $value;

                if ($value !== null) {
                    $value = match ($propertyType->getName()) {
                        'int' => (int) $value,
                        default => $value,
                    };
                }

                $property->setValue($object, $value);
            }
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function hydrateEmpty(object $object): void
    {
        $reflectionClass = new \ReflectionClass(get_class($object));
        foreach ($reflectionClass->getProperties() as $property) {
            $this->setDefault($property, $object);
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function convert(object $object): array
    {
        $reflectionClass = new \ReflectionClass($object);
        foreach ($reflectionClass->getProperties() as $property) {
            if (!$property->isInitialized($object)) {
                continue;
            }

            $value = $property->getValue($object);
            if ($value instanceof \UnitEnum) {
                $value = $value->value;
            } elseif (is_object($value)) {
                $value = $this->convert($value);
            }

            $result[$property->getName()] = $value;
        }
        return $result ?? [];
    }

    /**
     * @throws \ReflectionException
     */
    private function setDefault(\ReflectionProperty $property, $object): void
    {
        if ($property->isInitialized($object)) {
            return;
        }
        if (!($type = $property->getType())) {
            $property->setValue($object, null);
            return;
        }
        if ($type->allowsNull()) {
            $property->setValue($object, null);
            return;
        }

        $typeName = $type->getName();
        if (enum_exists($typeName)) {
            $reflectionEnum = new \ReflectionEnum($type->getName());
            if ($reflectionEnum->isBacked()) {
                $property->setValue($object, $reflectionEnum->getCases()[0]->getValue());
            }
            return;
        }

        switch ($typeName) {
            case 'string':
                $property->setValue($object, '');
                break;
            case 'int':
            case 'float':
                $property->setValue($object, 0);
                break;
            case 'bool':
                $property->setValue($object, false);
                break;
            case 'array':
                $property->setValue($object, []);
                break;
            default:
                if (class_exists($typeName)) {
                    $nestedObject = new $typeName;
                    $this->hydrateEmpty($nestedObject);
                    $property->setValue($object, $nestedObject);
                }
        }
    }
}