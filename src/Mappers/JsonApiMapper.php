<?php

namespace PlacetoPay\JsonApiMapper\Mappers;

use PlacetoPay\JsonApiMapper\Contracts\DocumentInterface;
use PlacetoPay\JsonApiMapper\Contracts\JsonApiMapperInterface;
use PlacetoPay\JsonApiMapper\Helper;

/**
 * Class JsonApiMapper
 * @package PlacetoPay\JsonApiMapper\Mappers
 */
class JsonApiMapper extends LoaderMapper implements JsonApiMapperInterface
{
    public function load($input, ?string $tag = DocumentInterface::KEYWORD_JSON_API)
    {
        return parent::load($input, $tag);
    }

    public function get(): JsonApiMapperInterface
    {
        return $this;
    }

    public function getVersion(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_VERSION, null);
    }
}
