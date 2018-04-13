<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\ObjectJsonApiMapperInterface;
use FreddieGar\JsonApiMapper\Helper;

/**
 * Class JsonApiMapper
 * @package FreddieGar\JsonApiMapper\Mappers
 *
 * @method string version() Alias to getVersion() method
 */
class ObjectJsonApiMapper extends Loader implements ObjectJsonApiMapperInterface
{
    public function load($input, ?string $tag = DocumentInterface::KEYWORD_JSON_API)
    {
        return parent::load($input, $tag);
    }

    public function get(): ObjectJsonApiMapperInterface
    {
        return $this;
    }

    public function getVersion(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_VERSION, null);
    }
}
