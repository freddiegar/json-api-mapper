<?php

namespace PlacetoPay\JsonApiMapper\Mappers;

use PlacetoPay\JsonApiMapper\Contracts\DocumentInterface;
use PlacetoPay\JsonApiMapper\Contracts\ErrorsMapperInterface;
use PlacetoPay\JsonApiMapper\Helper;
use PlacetoPay\JsonApiMapper\Traits\LinksMapperTrait;
use PlacetoPay\JsonApiMapper\Traits\MetaMapperTrait;

class ErrorsMapper extends LoaderMapper implements ErrorsMapperInterface
{
    use LinksMapperTrait,
        MetaMapperTrait;

    public function load($input, ?string $tag = DocumentInterface::KEYWORD_ERRORS): ErrorsMapperInterface
    {
        return parent::load($input, $tag);
    }

    public function get(?int $index = null): ?ErrorsMapperInterface
    {
        if (!is_null($index)) {
            if (isset($this->original()[$index])) {
                $this->current = $this->original()[$index];
            } else {
                return null;
            }
        }

        return $this;
    }

    public function getId(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_ID, null);
    }

    /**
     * @return null|string
     */
    public function getAbout(): ?string
    {
        return $this->getLinks()->getAbout();
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_STATUS, null);
    }

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_CODE, null);
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_TITLE, null);
    }

    /**
     * @return null|string
     */
    public function getDetail(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_DETAIL, null);
    }

    /**
     * @return array|null
     */
    public function getSource(): ?array
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ERRORS_SOURCE, null);
    }
}
