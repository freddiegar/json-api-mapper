<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface JsonApiMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 *
 * @method string version() Alias to getVersion() method
 * @property string $version Access magic to getVersion() method
 */
interface ObjectJsonApiMapperInterface extends LoaderInterface
{
    /**
     * @return ObjectJsonApiMapperInterface
     */
    public function get(): ObjectJsonApiMapperInterface;

    /**
     * @return null|string
     */
    public function getVersion(): ?string;
}
