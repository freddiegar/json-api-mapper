<?php

namespace FreddieGar\JsonApiMapper\Contracts;

interface ErrorsMapperInterface extends LoaderInterface
{
    /**
     * @param int|null $index
     * @return ErrorsMapperInterface
     */
    public function get(?int $index = null): ?ErrorsMapperInterface;

    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return null|string
     */
    public function getAbout(): ?string;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * @return null|string
     */
    public function getCode(): ?string;

    /**
     * @return null|string
     */
    public function getTitle(): ?string;

    /**
     * @return null|string
     */
    public function getDetail(): ?string;

    /**
     * @return array|null
     */
    public function getSource(): ?array;

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
