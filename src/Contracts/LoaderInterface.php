<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface LoaderInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 */
interface LoaderInterface
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

    /**
     * @return array
     */
    public function all(): array;
}
