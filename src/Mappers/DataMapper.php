<?php

namespace PlacetoPay\JsonApiMapper\Mappers;

use PlacetoPay\JsonApiMapper\Contracts\DataMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\DocumentInterface;
use PlacetoPay\JsonApiMapper\Contracts\IncludedMapperInterface;
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

    /**
     * @var IncludedMapperInterface
     */
    private $included;

    public function load($input, ?string $tag = DocumentInterface::KEYWORD_DATA)
    {
        //Verify if exist included in input to add attributes in relationships data
        $this->included = new IncludedMapper($input);

        return parent::load($input, $tag);
    }

    public function find(string $id): ?DataMapperInterface
    {
        foreach ($this->original() as $index => $data) {
            if ($data[DocumentInterface::KEYWORD_ID] == $id) {
                return $this->get($index);
            }
        }

        return null;
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
        $data = null;

        if (isset($this->getRelationships()[$relationName])) {
            $data = new DataMapper($this->getRelationships()[$relationName]);

            if ($data && $dataWithAttributes = $this->included->find($data->getType(), $data->getId())) {
                $data = $dataWithAttributes;
            }
        }

        return $data;
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
