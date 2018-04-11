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
     * Let it with type in return They are many types
     * @param string $path
     * @return null|int|string|array
     */
    public function getMeta(string $path);

    // Alias

    public function meta(string $path);
}
