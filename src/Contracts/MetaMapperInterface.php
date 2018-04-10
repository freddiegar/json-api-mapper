<?php

namespace FreddieGar\JsonApiMapper\Contracts;

interface MetaMapperInterface extends LoaderMapperInterface
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
}
