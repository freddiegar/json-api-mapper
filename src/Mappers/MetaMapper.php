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

    public function meta(string $path)
    {
        return $this->getMeta($path);
    }

    public function __get($name)
    {
        $name = $this->_sanitizeName($name);

        if ($property = $this->getMeta($name)) {
            return $property;
        }

        return parent::__get($name);
    }
}
