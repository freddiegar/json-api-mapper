<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\RelatedMapperInterface;
use FreddieGar\JsonApiMapper\Helper;

/**
 * Class LinksMapper
 * @package FreddieGar\JsonApiMapper\Mappers
 *
 * @method string self() Alias to getSelf() method
 * @method string first() Alias to getFirst() method
 * @method string prev() Alias to getPrev() method
 * @method string next() Alias to getNext() method
 * @method string last() Alias to getLast() method
 * @method string about() Alias to getAbout() method
 * @method null|string|RelatedMapperInterface related();
 */
class LinksMapper extends Loader implements LinksMapperInterface
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

    public function getRelated()
    {
        if (isset($this->current()[DocumentInterface::KEYWORD_RELATED])) {
            $related = new RelatedMapper($this->current());

            if (is_string($related->get())) {
                return $related->get();
            }

            return $related;
        }

        return null;
    }
}
