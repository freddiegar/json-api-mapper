<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface IncludedMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 *
 * @method DataMapperInterface|mixed included(int $index) Alias to getIncluded() method
 */
interface IncludedMapperInterface extends LoaderInterface
{
    /**
     * @param string $type
     * @param string $id
     * @return null|DataMapperInterface|mixed
     */
    public function find(string $type, string $id = null): ?DataMapperInterface;

    /**
     * @return IncludedMapperInterface
     */
    public function get(): IncludedMapperInterface;

    /**
     * @param int $index
     * @return null|DataMapperInterface|mixed
     */
    public function getIncluded(int $index): ?DataMapperInterface;
}
