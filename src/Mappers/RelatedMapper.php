<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\RelatedMapperInterface;
use FreddieGar\JsonApiMapper\Helper;
use FreddieGar\JsonApiMapper\Traits\MetaMapperTrait;

/**
 * Class LinksMapper
 * @package FreddieGar\JsonApiMapper\Mappers
 * @method string href() Alias to getHref() method
 * @method string|array meta(?string $path = null) Alias to getMeta() method
 */
class RelatedMapper extends Loader implements RelatedMapperInterface
{
    use MetaMapperTrait;

    public function load($input, ?string $tag = DocumentInterface::KEYWORD_RELATED)
    {
        return parent::load($input, $tag);
    }

    public function get()
    {
        if (is_string($this->original())) {
            return $this->original();
        }

        return $this;
    }

    public function getHref(): ?string
    {
        return Helper::getFromArray($this->current(), DocumentInterface::KEYWORD_HREF, null);
    }
}
