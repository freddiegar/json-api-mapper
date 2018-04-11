<?php

namespace FreddieGar\JsonApiMapper\Contracts;

interface LinksMapperInterface extends LoaderInterface
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
     * @return null|string|RelatedMapperInterface
     */
    public function getRelated();

    // Alias

    public function self(): ?string;

    public function first(): ?string;

    public function prev(): ?string;

    public function next(): ?string;

    public function last(): ?string;

    public function about(): ?string;

    /**
     * @return null|string|RelatedMapperInterface
     */
    public function related();
}
