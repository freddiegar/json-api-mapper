<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface DataMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 * @property string $id Access magic to getId() method
 * @property string $type Access magic to getType() method
 * @property array $attributes Access magic to getAttributes() method
 * @property string|object $attribute Access magic to getAttribute() method
 * @property array $relationships Access magic to getRelationships() method
 * @property DataMapperInterface|object $relationship Access magic to getRelationship() method
 * @property LinksMapperInterface $links Access magic to getLinks() method
 */
interface DataMapperInterface extends LoaderInterface
{
    /**
     * @param string $id
     * @return null|DataMapperInterface
     */
    public function find(string $id): ?DataMapperInterface;

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

    // Alias

    public function id(): ?string;

    public function type(): ?string;

    public function attributes(): array;

    public function relationships(): array;

    public function attribute(string $attributeName, $default = null): ?string;

    public function relationship(string $relationName): ?DataMapperInterface;

    public function meta(?string $path = null);

    public function links(): ?LinksMapperInterface;
}
