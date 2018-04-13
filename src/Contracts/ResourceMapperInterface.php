<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface ResourceMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 *
 * @method DataMapperInterface data(?int $index = null)
 * @method ErrorsMapperInterface errors(?int $index = null)
 * @method MetaMapperInterface meta()
 * @method ObjectJsonApiMapperInterface jsonApi()
 * @method LinksMapperInterface links()
 * @method IncludedMapperInterface included()
 *
 * @property DataMapperInterface $data Access magic to getData() method
 * @property ErrorsMapperInterface $errors Access magic to getErrors() method
 * @property MetaMapperInterface $meta Access magic to getMeta() method
 * @property ObjectJsonApiMapperInterface $jsonapi Access magic to getJsonApi() method
 * @property IncludedMapperInterface $included Access magic to getIncluded() method
 * @property LinksMapperInterface $links Access magic to getLinks() method
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
