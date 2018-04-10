<?php

namespace PlacetoPay\JsonApiMapper\Mappers;

use PlacetoPay\JsonApiMapper\Contracts\DocumentInterface;
use PlacetoPay\JsonApiMapper\Contracts\LinksMapperInterface;
use PlacetoPay\JsonApiMapper\Helper;

/**
 * Class LinksMapper
 * @package PlacetoPay\JsonApiMapper\Mappers
 */
class LinksMapper extends LoaderMapper implements LinksMapperInterface
{
    public function load($input, ?string $tag = DocumentInterface::KEYWORD_LINKS)
    {
        return parent::load($input, $tag);
    }

    public function get(): LinksMapperInterface
    {
        return $this;
    }

    public function getSelf(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_SELF, null);
    }

    public function getHref(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_HREF, null);
    }

    public function getFirst(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_FIRST, null);
    }

    public function getPrev(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_PREV, null);
    }

    public function getNext(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_NEXT, null);
    }

    public function getLast(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_LAST, null);
    }

    public function getAbout(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_ABOUT, null);
    }

    public function getRelated(?string $path = null)
    {
        $related = Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_RELATED, null);

        if (!is_null($path)) {
            return Helper::getFromArray($related, $path, null);
        }

        return $related;
    }
}
