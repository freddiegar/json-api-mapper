<?php

namespace FreddieGar\JsonApiMapper\Contracts;

interface LinksMapperInterface extends LoaderMapperInterface
{
    /**
     * @return LinksMapperInterface
     */
    public function get(): LinksMapperInterface;

    /**
     * @return null|string
     */
    public function getSelf(): ?string;

    /**
     * @return null|string
     */
    public function getHref(): ?string;

    /**
     *
     * @return null|string
     */
    public function getFirst(): ?string;

    /**
     * @return null|string
     */
    public function getPrev(): ?string;

    /**
     * @return null|string
     */
    public function getNext(): ?string;

    /**
     * @return null|string
     */
    public function getLast(): ?string;

    /**
     * @return null|string
     */
    public function getAbout(): ?string;

    /**
     * @param string $path
     * @return null|string|array
     */
    public function getRelated(?string $path = null);
}
