<?php

namespace PlacetoPay\JsonApiMapper\Contracts;

interface JsonApiMapperInterface extends LoaderMapperInterface
{
    /**
     * @return JsonApiMapperInterface
     */
    public function get(): JsonApiMapperInterface;

    /**
     * @return null|string
     */
    public function getVersion(): ?string;
}
