<?php

namespace PlacetoPay\JsonApiMapper\Mappers;

use PlacetoPay\JsonApiMapper\Contracts\DataMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\DocumentInterface;
use PlacetoPay\JsonApiMapper\Contracts\IncludedMapperInterface;

/**
 * Class IncludedMapper
 * @package PlacetoPay\JsonApiMapper\Mappers
 */
class IncludedMapper extends LoaderMapper implements IncludedMapperInterface
{

    public function load($input, ?string $tag = DocumentInterface::KEYWORD_INCLUDED)
    {
        return parent::load($input, $tag);
    }

    public function get(): IncludedMapperInterface
    {
        return $this;
    }

    public function getIncluded(int $index): ?DataMapperInterface
    {
        return isset($this->current()[$index])
            ? new DataMapper([DocumentInterface::KEYWORD_DATA => $this->current()[$index]])
            : null;
    }
}
