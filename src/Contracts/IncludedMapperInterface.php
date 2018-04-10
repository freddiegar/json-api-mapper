<?php

namespace PlacetoPay\JsonApiMapper\Contracts;

interface IncludedMapperInterface extends LoaderMapperInterface
{
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
