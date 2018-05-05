<?php

namespace FreddieGar\JsonApiMapper\Traits;

use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Mappers\LinksMapper;

/**
 * Trait LinksMapperTrait
 * @package FreddieGar\JsonApiMapper\Traits
 */
trait LinksMapperTrait
{
    public function getLinks(): ?LinksMapperInterface
    {
        return isset($this->current()[DocumentInterface::KEYWORD_LINKS])
            ? new LinksMapper($this->current())
            : null;
    }

    // Alias

    public function links(): ?LinksMapperInterface
    {
        return $this->getLinks();
    }
}
