<?php

namespace Maldoinc\Doctrine\Filter;

use Doctrine\ORM\QueryBuilder;
use Maldoinc\Doctrine\Filter\Annotation\Expose as FilterExpose;

class ExposedFieldsReader
{
    public function __construct()
    {
    }

    /**
     * @phpstan-return array<class-string, array<string, string>>
     */
    public function readExposedFields(QueryBuilder $queryBuilder): array
    {
        $res = [];

        /** @var class-string $entity */
        foreach ($queryBuilder->getRootEntities() as $entity) {
            $res[$entity] = $this->readFieldsFromClass($entity);
        }

        return $res;
    }

    /**
     * @psalm-param class-string $class
     * @psalm-return array<string, string>
     * @return array
     */
    private function readFieldsFromClass(string $class)
    {
        $result = [];
        $reflectionClass = new \ReflectionClass($class);

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $exposeAnnotations = $reflectionProperty->getAttributes(FilterExpose::class);
            if (count($exposeAnnotations) > 0) {
                $exposeAnnotation = $exposeAnnotations[0];
                $arguments = $exposeAnnotation->getArguments();

                $serializedName = array_key_exists('serializedName', $arguments) ? $arguments['serializedName'] : $reflectionProperty->getName();

                $result[$serializedName] = $reflectionProperty->getName();
            }
        }

        return $result;
    }
}
