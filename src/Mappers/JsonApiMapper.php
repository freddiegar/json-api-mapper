<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\JsonApiMapperInterface;
use FreddieGar\JsonApiMapper\Helper;

/**
 * Class JsonApiMapper
 * @package FreddieGar\JsonApiMapper\Mappers
 */
class JsonApiMapper extends Loader implements JsonApiMapperInterface
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

    public function version(): ?string
    {
        return $this->getVersion();
    }
}
