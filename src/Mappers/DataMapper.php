<?php

namespace PlacetoPay\JsonApiMapper\Mappers;

use PlacetoPay\JsonApiMapper\Contracts\DocumentInterface;
use PlacetoPay\JsonApiMapper\Contracts\DataMapperInterface;
use PlacetoPay\JsonApiMapper\Helper;
use PlacetoPay\JsonApiMapper\Traits\LinksMapperTrait;
use PlacetoPay\JsonApiMapper\Traits\MetaMapperTrait;

/**
 * Class DataMapper
 * @package PlacetoPay\JsonApiMapper\Mappers
 */
class DataMapper extends LoaderMapper implements DataMapperInterface
{
    use LinksMapperTrait,
        MetaMapperTrait;

    public function load($input, ?string $tag = DocumentInterface::KEYWORD_DATA)
    {
        return parent::load($input, $tag);
    }

    public function get(?int $index = null): ?DataMapperInterface
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
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ID, null);
    }

    public function getType(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_TYPE, null);
    }

    public function getAttributes(): array
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_ATTRIBUTES, []);
    }

    public function getRelationships(): array
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_RELATIONSHIPS, []);
    }

    public function getAttribute(string $attributeName, $default = null): ?string
    {
        return Helper::getFromArray($this->getAttributes(), $attributeName, $default);
    }

    public function getRelationship(string $relationName): ?DataMapperInterface
    {
        return isset($this->getRelationships()[$relationName])
            ? new DataMapper($this->getRelationships()[$relationName])
            : null;
    }

//    public function getResource($withAttributes = true): array
//    {
//        $resource = [
//            DocumentInterface::KEYWORD_ID => $this->getId(),
//            DocumentInterface::KEYWORD_TYPE => $this->getType(),
//        ];
//
//        return $withAttributes
//            ? $resource + $this->getAttributes()
//            : $resource;
//    }
}
