<?php

namespace PlacetoPay\JsonApiMapper\Contracts;

interface IncludedMapperInterface extends LoaderMapperInterface
{
    /**
     * @param int $id
     * @return null|DataMapperInterface
     */
    public function find(int $id): ?DataMapperInterface;

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
