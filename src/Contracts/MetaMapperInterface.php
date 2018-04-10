<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface MetaMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 */
interface MetaMapperInterface extends LoaderInterface
{
    /**
     * @return MetaMapperInterface
     */
    public function get(): MetaMapperInterface;

    /**
     * @param string $path
     * @return null|string|array
     */
    public function getMeta(string $path);

    // Alias

    public function meta(string $path);
}
