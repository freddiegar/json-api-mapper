<?php

namespace FreddieGar\JsonApiMapper\Contracts;

interface IncludedMapperInterface extends LoaderInterface
{
    /**
     * @param string $type
     * @param string|null $id
     * @return null|DataMapperInterface
     */
    public function find(string $type, string $id = null): ?DataMapperInterface;

    /**
     * @return IncludedMapperInterface
     */
    public function get(): IncludedMapperInterface;

    /**
     * @param int $index
     * @return null|DataMapperInterface
     */
    public function getIncluded(int $index): ?DataMapperInterface;
}
