<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface ResourceMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 *
 * @method DataMapperInterface|mixed data(?int $index = null)
 * @method ErrorsMapperInterface|mixed errors(?int $index = null)
 * @method MetaMapperInterface|mixed meta()
 * @method ObjectJsonApiMapperInterface|mixed jsonApi()
 * @method LinksMapperInterface|mixed links()
 * @method IncludedMapperInterface|mixed included()
 *
 * @property DataMapperInterface|mixed $data Access magic to getData() method
 * @property ErrorsMapperInterface|mixed $errors Access magic to getErrors() method
 * @property MetaMapperInterface|mixed $meta Access magic to getMeta() method
 * @property ObjectJsonApiMapperInterface|mixed $jsonapi Access magic to getJsonApi() method
 * @property IncludedMapperInterface|mixed $included Access magic to getIncluded() method
 * @property LinksMapperInterface|mixed $links Access magic to getLinks() method
 */
interface ResourceMapperInterface extends LoaderInterface
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
     * @return ObjectJsonApiMapperInterface|null
     */
    public function getJsonApi(): ?ObjectJsonApiMapperInterface;

    /**
     * @return LinksMapperInterface|null
     */
    public function getLinks(): ?LinksMapperInterface;

    /**
     * @return IncludedMapperInterface|null
     */
    public function getIncluded(): ?IncludedMapperInterface;
}
