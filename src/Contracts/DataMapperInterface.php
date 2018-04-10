<?php

namespace FreddieGar\JsonApiMapper\Contracts;

interface DataMapperInterface extends LoaderMapperInterface
{
    /**
     * @param string $id
     * @return null|DataMapperInterface
     */
    public function find(string $id): ?DataMapperInterface;

    /**
     * @param int|null $index
     * @return null|DataMapperInterface
     */
    public function get(?int $index = null): ?DataMapperInterface;

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

    /**
     * @return null|string
     */
    public function getLinkSelf(): ?string;

    /**
     * @return array
     */
    public function getLinkRelated(): ?array;
}
