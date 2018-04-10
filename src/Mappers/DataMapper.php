<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\IncludedMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Exceptions\JsonApiMapperException;
use FreddieGar\JsonApiMapper\Helper;
use FreddieGar\JsonApiMapper\Traits\LinksMapperTrait;
use FreddieGar\JsonApiMapper\Traits\MetaMapperTrait;

/**
 * Class DataMapper
 * @package FreddieGar\JsonApiMapper\Mappers
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

    public function id(): ?string
    {
        return $this->getId();
    }

    public function type(): ?string
    {
        return $this->getType();
    }

    public function attributes(): array
    {
        return $this->getAttributes();
    }

    public function relationships(): array
    {
        return $this->getRelationships();
    }

    public function attribute(string $attributeName, $default = null): ?string
    {
        return $this->getAttribute($attributeName, $default);
    }

    public function relationship(string $relationName): ?DataMapperInterface
    {
        return $this->getRelationship($relationName);
    }

    public function meta(?string $path = null)
    {
        return $this->getMeta($path);
    }

    public function links(): ?LinksMapperInterface
    {
        return $this->getLinks();
    }

    /**
     * @param $call
     * @param $arguments
     * @return mixed
     * @throws JsonApiMapperException
     */
    public function __call($call, $arguments)
    {
        $needle = null;
        $needle = null;
        $call = strlen($call) > 3 && substr($call, 0, 3) === 'get'
            ? substr($call, 3)
            : $call;

        /**
         * Use get{Attribute} method to get attributes, by example: getTitle(), getAge();
         */
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', ' $0', $call));

        if (strpos($temp, '_') !== false) {
            $temp = preg_replace(['_ ', '_'], ' ', $temp);
        }

        $needle = $needle = str_replace(' ', '-', $temp);

//        if (!in_array($needle, ['title', 'body', 'created', 'updated-at', 'invalid'])) {
//            var_dump($call, $needle, $this->getAttributes(), $this->getRelationships());
//            die;
//        }

        if ($needle && array_key_exists($needle, $this->getRelationships())) {
            return $this->getRelationship($needle);
        }

        if ($needle && array_key_exists($needle, $this->getAttributes())) {
            return $this->getAttribute($needle);
        }

//        var_dump(get_defined_vars());
//        die;
        if (method_exists($this, $call)) {
            return $this->{$call}($arguments);
        }

        if (method_exists($this, $method = sprintf('get%s', $call))) {
            return $this->$method($arguments);
        }

        return null;
    }
}
