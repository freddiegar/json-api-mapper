<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\IncludedMapperInterface;

/**
 * Class IncludedMapper
 * @package FreddieGar\JsonApiMapper\Mappers
 */
class IncludedMapper extends Loader implements IncludedMapperInterface
{

    public function load($input, ?string $tag = DocumentInterface::KEYWORD_INCLUDED)
    {
        return parent::load($input, $tag);
    }

    public function find(string $type, string $id): ?DataMapperInterface
    {
        if (is_array($this->original())) {
            foreach ($this->original() as $index => $data) {
                if ($data[DocumentInterface::KEYWORD_TYPE] == $type
                    && $data[DocumentInterface::KEYWORD_ID] == $id) {
                    return $this->getIncluded($index);
                }
            }
        }

        return null;
    }

    public function get(): IncludedMapperInterface
    {
        return $this;
    }

    public function getIncluded(int $index): ?DataMapperInterface
    {
        return isset($this->current()[$index])
            ? new DataMapper([DocumentInterface::KEYWORD_DATA => $this->current()[$index]])
            : null;
    }

    public function included(int $index): ?DataMapperInterface
    {
        return $this->getIncluded($index);
    }
}
