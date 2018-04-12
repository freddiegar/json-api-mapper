<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface JsonApiMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 *
 * @method string version() Alias to getVersion() method
 * @property string $version Access magic to getVersion() method
 */
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
}
