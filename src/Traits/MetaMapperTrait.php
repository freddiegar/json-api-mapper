<?php

namespace FreddieGar\JsonApiMapper\Traits;

use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Helpers\Helper;

/**
 * Trait MetaMapperTrait
 * @package FreddieGar\JsonApiMapper\Traits
 */
trait MetaMapperTrait
{
    public function getMeta(?string $path = null)
    {
        $meta = Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_META, null);

        if (is_array($meta) && !is_null($path)) {
            $meta = Helper::getFromArray($meta, $path, null);
        }

        return $meta;
    }

    // Alias

    public function meta(?string $path = null)
    {
        return $this->getMeta($path);
    }
}
