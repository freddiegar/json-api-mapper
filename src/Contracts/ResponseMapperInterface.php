<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface ResponseMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 *
 * @property DataMapperInterface $data Access magic to getData() method
 * @property ErrorsMapperInterface $errors Access magic to getErrors() method
 * @property MetaMapperInterface $meta Access magic to getMeta() method
 * @property JsonApiMapperInterface $jsonapi Access magic to getJsonApi() method
 * @property IncludedMapperInterface $included Access magic to getIncluded() method
 * @property LinksMapperInterface $links Access magic to getLinks() method
 */
interface ResponseMapperInterface extends LoaderInterface
{
    /**
     * @param int|null $index
     * @return DataMapperInterface|mixed|null
     */
    public function getData(?int $index = null);

    /**
     * @param int|null $index
     * @return ErrorsMapperInterface|null
     */
    public function getErrors(?int $index = null): ?ErrorsMapperInterface;

    /**
     * @return MetaMapperInterface|null
     */
    public function getMeta(): ?MetaMapperInterface;

    /**
     * @return JsonApiMapperInterface|null
     */
    public function getJsonApi(): ?JsonApiMapperInterface;

    /**
     * @return LinksMapperInterface|null
     */
    public function getLinks(): ?LinksMapperInterface;

    /**
     * @return IncludedMapperInterface|null
     */
    public function getIncluded(): ?IncludedMapperInterface;

    // Alias

    public function data(?int $index = null): ?DataMapperInterface;

    public function errors(?int $index = null): ?ErrorsMapperInterface;

    public function meta(): ?MetaMapperInterface;

    public function jsonApi(): ?JsonApiMapperInterface;

    public function links(): ?LinksMapperInterface;

    public function included(): ?IncludedMapperInterface;
}
