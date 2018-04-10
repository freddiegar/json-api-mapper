<?php

namespace FreddieGar\JsonApiMapper\Contracts;

interface IncludedMapperInterface extends LoaderInterface
{
    /**
     * @param string $type
     * @param string $id
     * @return DataMapperInterface|null
     */
    public function find(string $type, string $id): ?DataMapperInterface;

    /**
     * @return IncludedMapperInterface
     */
    public function get(): IncludedMapperInterface;

    /**
     * @param int $index
     * @return null|DataMapperInterface
     */
    public function getIncluded(int $index): ?DataMapperInterface;

    // Alias

    public function included(int $index): ?DataMapperInterface;
}
