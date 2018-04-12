<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface RelatedMapperInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 *
 * @method string href() Alias to getHref() method
 * @method string|array meta(?string $path = null) Alias to getMeta() method
 *
 * @property string $href Access magic to getHref() method
 * @property array $meta Access magic to getMeta() method
 */
interface RelatedMapperInterface extends LoaderInterface
{
    /**
     * @return null|string|RelatedMapperInterface
     */
    public function get();

    /**
     * @return null|string
     */
    public function getHref(): ?string;

    /**
     * @param null|string $path
     * @return null|string|array
     */
    public function getMeta(?string $path = null);
}
