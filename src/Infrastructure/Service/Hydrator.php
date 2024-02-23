<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

final readonly class Hydrator
{
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
                $typeName = $propertyType->getName();
                if (class_exists($typeName)) {
                    $nestedObject = new $typeName;
                    $this->hydrate($value, $nestedObject);
                    $property->setValue($object, $nestedObject);
                }
            } else {
                $property->setValue($object, $value);
            }
        }
    }

    public function hydrateEmpty(object $object): void
    {
        $reflectionClass = new \ReflectionClass(get_class($object));
        foreach ($reflectionClass->getProperties() as $property) {
            $this->setDefault($property, $object);
        }
    }

    public function convert(object $object): array
    {
        $reflectionClass = new \ReflectionClass($object);
        foreach ($reflectionClass->getProperties() as $property) {
            if (!$property->isInitialized($object)) {
                $this->setDefault($property, $object);
            }
            $value = $property->getValue($object);

            if (is_object($value)) {
                $value = $this->convert($value);
            }

            $result[$property->getName()] = $value;
        }
        return $result ?? [];
    }

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