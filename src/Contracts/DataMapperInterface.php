<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface DataMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 *
 * @method string id() Alias to getId() method
 * @method string type() Alias to getType() method
 * @method array  attributes() Alias to getAttributes() method
 * @method array relationships() Alias to getRelationships() method
 * @method string attribute(string $attributeName, $default = null) Alias to getAttribute() method
 * @method DataMapperInterface|mixed relationship(string $relationName) ?Alias to getRelationship() method
 * @method array|string|mixed meta(?string $path = null) Alias to getMeta() method
 * @method LinksMapperInterface|mixed links() Alias to getLinks() method
 *
 * @property string $id Access magic to getId() method
 * @property string $type Access magic to getType() method
 * @property array $attributes Access magic to getAttributes() method
 * @property string|mixed $attribute Access magic to getAttribute() method
 * @property array $relationships Access magic to getRelationships() method
 * @property DataMapperInterface|mixed $relationship Access magic to getRelationship() method
 * @property LinksMapperInterface|mixed $links Access magic to getLinks() method
 */
interface DataMapperInterface extends LoaderInterface
{
    /**
     * @param string $id
     * @return null|DataMapperInterface|mixed
     */
    public function find(string $uuid): ?DataMapperInterface;

    /**
     * @param int|null $index
     * @return null|array|DataMapperInterface
     */
    public function get(?int $index = null);

    /**
     * @return null|string
     */
    public function getId(): ?string;

    /**
     * @return null|string
     */
    public function getType(): ?string;

    /**
     * @return array
     */
    public function getAttributes(): array;

    /**
     * @return array
     */
    public function getRelationships(): array;

    /**
     * @param string $attributeName
     * @param null $default
     * @return null|string
     */
    public function getAttribute(string $attributeName, $default = null): ?string;

    /**
     * @param string $relationName
     * @return null|DataMapperInterface
     */
    public function getRelationship(string $relationName): ?DataMapperInterface;

    /**
     * @param null|string $path
     * @return mixed|null
     */
    public function getMeta(?string $path = null);

    /**
     * @return null|LinksMapperInterface
     */
    public function getLinks(): ?LinksMapperInterface;
}
