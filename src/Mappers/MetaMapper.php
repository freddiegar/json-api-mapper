<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\MetaMapperInterface;
use FreddieGar\JsonApiMapper\Helper;

/**
 * Class MetaMapper
 * @package FreddieGar\JsonApiMapper\Mappers
 */
class MetaMapper extends Loader implements MetaMapperInterface
{
    public function load($input, ?string $tag = DocumentInterface::KEYWORD_META)
    {
        return parent::load($input, $tag);
    }

    public function get(): MetaMapperInterface
    {
        return $this;
    }

    public function getMeta(string $path)
    {
        return Helper::getFromArray($this->current(), $path, null);
    }
}
