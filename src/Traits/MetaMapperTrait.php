<?php

namespace FreddieGar\JsonApiMapper\Traits;

use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Helper;

/**
 * Trait MetaMapperTrait
 * @package FreddieGar\JsonApiMapper\Traits
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