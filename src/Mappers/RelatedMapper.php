<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use Exception;
use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\RelatedMapperInterface;
use FreddieGar\JsonApiMapper\Helper;
use FreddieGar\JsonApiMapper\Traits\MetaMapperTrait;

/**
 * Class LinksMapper
 * @package FreddieGar\JsonApiMapper\Mappers
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

    public function href(): ?string
    {
        return $this->getHref();
    }
}
