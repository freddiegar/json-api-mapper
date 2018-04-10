<?php

namespace PlacetoPay\JsonApiMapper\Mappers;

use PlacetoPay\JsonApiMapper\Contracts\DocumentInterface;
use PlacetoPay\JsonApiMapper\Contracts\MetaMapperInterface;
use PlacetoPay\JsonApiMapper\Helper;

/**
 * Class MetaMapper
 * @package PlacetoPay\JsonApiMapper\Mappers
 */
class MetaMapper extends LoaderMapper implements MetaMapperInterface
{
    public function load($input, ?string $tag = DocumentInterface::KEYWORD_META)
    {
        return parent::load($input, $tag);
    }

    public function get(): MetaMapperInterface
    {
        return $this;
    }

    public function getPath(string $path)
    {
        return Helper::getFromArray($this->current(), $path, null);
    }
}
