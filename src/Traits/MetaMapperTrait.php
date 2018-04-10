<?php

namespace PlacetoPay\JsonApiMapper\Traits;

use PlacetoPay\JsonApiMapper\Contracts\DocumentInterface;
use PlacetoPay\JsonApiMapper\Helper;

/**
 * Trait MetaMapperTrait
 * @package PlacetoPay\JsonApiMapper\Traits
 */
trait MetaMapperTrait
{
    public function getMeta(?string $path = null)
    {
        $meta = Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_META, []);

        if (!is_null($path)) {
            $meta = Helper::getFromArray($meta, $path, null);
        }

        return $meta;
    }
}