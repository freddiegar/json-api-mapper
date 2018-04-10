<?php

namespace FreddieGar\JsonApiMapper\Contracts;

interface JsonApiMapperInterface extends LoaderInterface
{
    /**
     * @return JsonApiMapperInterface
     */
    public function get(): JsonApiMapperInterface;

    /**
     * @return null|string
     */
    public function getVersion(): ?string;

    // Alias

    public function version(): ?string;
}
