<?php

namespace PlacetoPay\JsonApiMapper\Traits;

use PlacetoPay\JsonApiMapper\Contracts\DocumentInterface;
use PlacetoPay\JsonApiMapper\Contracts\LinksMapperInterface;
use PlacetoPay\JsonApiMapper\Mappers\LinksMapper;

/**
 * Trait LinksMapperTrait
 * @package PlacetoPay\JsonApiMapper\Traits
 */
trait LinksMapperTrait
{
    public function getLinks(): ?LinksMapperInterface
    {
        return isset($this->current()[DocumentInterface::KEYWORD_LINKS])
            ? new LinksMapper($this->current())
            : null;
    }

    public function getLinkSelf(): ?string
    {
        return $this->getLinks()->getSelf();
    }

    public function getLinkRelated(): ?array
    {
        return $this->getLinks()->getRelated();
    }
}