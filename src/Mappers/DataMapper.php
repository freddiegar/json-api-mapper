<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\IncludedMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Helpers\Helper;
use FreddieGar\JsonApiMapper\Traits\LinksMapperTrait;
use FreddieGar\JsonApiMapper\Traits\MetaMapperTrait;

/**
 * Class DataMapper
 * @package FreddieGar\JsonApiMapper\Mappers
 * @method string id() Alias to getId() method
 * @method string type() Alias to getType() method
 * @method array  attributes() Alias to getAttributes() method
 * @method array relationships() Alias to getRelationships() method
 * @method string attribute(string $attributeName, $default = null) Alias to getAttribute() method
 * @method DataMapperInterface relationship(string $relationName) ?Alias to getRelationship() method
 * @method array|string meta(?string $path = null) Alias to getMeta() method
 * @method LinksMapperInterface links() Alias to getLinks() method
 */
class DataMapper extends Loader implements DataMapperInterface
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
        foreach ($this->all() as $index => $data) {
            if ($data[DocumentInterface::KEYWORD_ID] == $id) {
                return $this->get($index);
            }
        }

        return null;
    }

    public function get(?int $index = null)
    {
        if (is_array($this->original()) && !is_null($index)) {
            if (isset($this->original()[$index])) {
                $this->current = $this->original()[$index];
                return $this;
            } else {
                return null;
            }
        }

        if ($this->count() === 0) {
            return $this->original();
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

    /**
     * @param $name
     * @param $arguments
     * @return DataMapperInterface|null|string
     */
    public function __call($name, $arguments)
    {
        $name = $arguments[0] ?? $name;

        if ($property = $this->magic($name)) {
            return $property;
        }

        return parent::__call($name, $arguments);
    }

    /**
     * @param $name
     * @return DataMapperInterface|DataMapper|null|string
     */
    public function __get($name)
    {
        /**
         * Getting attributes a relationships
         */
        if (in_array($name, [DocumentInterface::KEYWORD_ATTRIBUTE, DocumentInterface::KEYWORD_RELATIONSHIP])) {
            return $this;
        }

        if ($property = $this->magic($name)) {
            return $property;
        }

        return parent::__get($name);
    }

    /**
     * @param $name
     * @return $this|DataMapperInterface|null|string
     */
    private function magic($name)
    {
        $name = $this->_sanitizeName($name);

        if ($property = $this->getRelationship($name)) {
            return $property;
        }

        if ($property = $this->getAttribute($name)) {
            return $property;
        }

        return null;
    }
}
