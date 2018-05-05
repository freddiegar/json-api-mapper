<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\IncludedMapperInterface;

/**
 * Class IncludedMapper
 * @package FreddieGar\JsonApiMapper\Mappers
 *
 * @method DataMapperInterface included(int $index) Alias to getIncluded() method
 */
class IncludedMapper extends Loader implements IncludedMapperInterface
{

    public function load($input, ?string $tag = DocumentInterface::KEYWORD_INCLUDED)
    {
        return parent::load($input, $tag);
    }

    public function find(string $type, string $id = null): ?DataMapperInterface
    {
        if (empty($type)) {
            return null;
        }

        $data = [];

        foreach ($this->all() as $index => $included) {
            if ($included[DocumentInterface::KEYWORD_TYPE] == $type) {
                if (is_null($id) && isset($this->current()[$index])) {
                    $data[] = $this->current()[$index];
                } elseif ($included[DocumentInterface::KEYWORD_ID] == $id) {
                    return $this->getIncluded($index);
                }
            }
        }

        if (!empty($data)) {
            return new DataMapper([DocumentInterface::KEYWORD_DATA => $data]);
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

    public function __call($name, $arguments)
    {
        return $this->getIncluded($arguments[0]);
    }
}
