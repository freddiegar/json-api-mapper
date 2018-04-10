<?php

namespace PlacetoPay\JsonApiMapper\Contracts;

interface IncludedMapperInterface extends LoaderMapperInterface
{
    /**
     * @param string $type
     * @param int|null $id
     * @return null|DataMapperInterface
     */
    public function find(string $type, int $id = null): ?DataMapperInterface;

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
