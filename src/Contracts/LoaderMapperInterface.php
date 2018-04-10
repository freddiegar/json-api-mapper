<?php

namespace FreddieGar\JsonApiMapper\Contracts;

interface LoaderMapperInterface
{
    /**
     * LoaderMapperInterface constructor.
     * @param null $input
     */
    public function __construct($input = null);

    /**
     * @param array|string $input
     * @param null|string $tag
     * @return mixed|$this
     */
    public function load($input, ?string $tag = null);

    /**
     * @return int
     */
    public function count(): int;
}
