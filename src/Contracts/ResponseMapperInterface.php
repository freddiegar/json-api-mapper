<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface ResponseMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 */
interface ResponseMapperInterface extends LoaderInterface
{
    /**
     * @param int|null $index
     * @return null|DataMapperInterface
     */
    public function getData(?int $index = null): ?DataMapperInterface;

    /**
     * @param int|null $index
     * @return null|ErrorsMapperInterface
     */
    public function getErrors(?int $index = null): ?ErrorsMapperInterface;

    /**
     * @return MetaMapperInterface
     */
    public function getMeta(): MetaMapperInterface;

    /**
     * @return JsonApiMapperInterface
     */
    public function getJsonApi(): JsonApiMapperInterface;

    /**
     * @return LinksMapperInterface
     */
    public function getLinks(): LinksMapperInterface;

    /**
     * @return IncludedMapperInterface
     */
    public function getIncluded(): IncludedMapperInterface;

    // Alias methods

    public function data(?int $index = null): ?DataMapperInterface;

    public function errors(?int $index = null): ?ErrorsMapperInterface;

    public function meta(): MetaMapperInterface;

    public function jsonApi(): JsonApiMapperInterface;

    public function links(): LinksMapperInterface;

    public function included(): IncludedMapperInterface;
}
