<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Helper;

/**
 * Class LinksMapper
 * @package FreddieGar\JsonApiMapper\Mappers
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

        if ($related && !is_null($path)) {
            return Helper::getFromArray($related, $path, null);
        }

        return $related;
    }

    public function self(): ?string
    {
        return $this->getSelf();
    }

    public function href(): ?string
    {
        return $this->getHref();
    }

    public function first(): ?string
    {
        return $this->getFirst();
    }

    public function prev(): ?string
    {
        return $this->getPrev();
    }

    public function next(): ?string
    {
        return $this->getNext();
    }

    public function last(): ?string
    {
        return $this->getLast();
    }

    public function about(): ?string
    {
        return $this->getAbout();
    }

    public function related(?string $path = null)
    {
        return $this->getRelated($path);
    }

    public function __get($name)
    {
        $name = $this->_sanitizeName($name);

        /**
         * Getting attributes a related
         */
        if (in_array($name, [DocumentInterface::KEYWORD_RELATED])) {
            return $this->getRelated();
        }

        return parent::__get($name);
    }
}
