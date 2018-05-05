<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use Exception;
use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\DocumentInterface;
use FreddieGar\JsonApiMapper\Contracts\ErrorsMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\IncludedMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\ObjectJsonApiMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\MetaMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\ResourceMapperInterface;
use InvalidArgumentException;

/**
 * Class ResourceMapper
 * @package FreddieGar\JsonApiMapper\Mappers
 *
 * @method DataMapperInterface|mixed data(?int $index = null)
 * @method ErrorsMapperInterface|mixed errors(?int $index = null)
 * @method MetaMapperInterface|mixed meta()
 * @method ObjectJsonApiMapperInterface|mixed jsonApi()
 * @method LinksMapperInterface|mixed links()
 * @method IncludedMapperInterface|mixed included()
 */
class ResourceMapper extends Loader implements ResourceMapperInterface
{
    /**
     * @var null
     */
    private $response = null;

    /**
     * @var DataMapperInterface
     */
    private $dataMapper;

    /**
     * @var ErrorsMapperInterface
     */
    private $errorsMapper;

    /**
     * @var MetaMapperInterface
     */
    private $metaMapper;

    /**
     * @var LinksMapperInterface
     */
    private $linksMapper;

    /**
     * @var ObjectJsonApiMapperInterface
     */
    private $jsonApiMapper;

    /**
     * @var IncludedMapperInterface
     */
    private $includedMapper;

    public function load($input, ?string $tag = null)
    {
        if (is_null($input)) {
            return $this;
        }

        try {
            if (is_array($input)) {
                $response = $input;
            } elseif (is_string($input)) {
                $response = json_decode($input, true);
            } elseif (is_object($input)) {
                $response = json_decode(json_encode($input), true);
            } else {
                throw new InvalidArgumentException('Format response is invalid: ' . print_r($input, true));
            }
        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        $this->response = $response;

        $this->dataMapper = new DataMapper($this->response);
        $this->errorsMapper = new ErrorsMapper($this->response);
        $this->metaMapper = new MetaMapper($this->response);
        $this->jsonApiMapper = new ObjectJsonApiMapper($this->response);
        $this->linksMapper = new LinksMapper($this->response);
        $this->includedMapper = new IncludedMapper($this->response);

        return $this;
    }

    public function getData(?int $index = null)
    {
        return $this->dataMapper->count() > 0
            ? $this->dataMapper->get($index)
            : $this->original();
    }

    public function getErrors(?int $index = null): ?ErrorsMapperInterface
    {
        return $this->errorsMapper->count() > 0
            ? $this->errorsMapper->get($index)
            : null;
    }

    public function getMeta(): ?MetaMapperInterface
    {
        return $this->metaMapper->count() > 0
            ? $this->metaMapper->get()
            : null;
    }

    public function getJsonApi(): ?ObjectJsonApiMapperInterface
    {
        return $this->jsonApiMapper->count() > 0
            ? $this->jsonApiMapper->get()
            : null;
    }

    public function getLinks(): ?LinksMapperInterface
    {
        return $this->linksMapper->count() > 0
            ? $this->linksMapper->get()
            : null;
    }

    public function getIncluded(): ?IncludedMapperInterface
    {
        return $this->includedMapper->count()
            ? $this->includedMapper->get()
            : null;
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, [
            DocumentInterface::KEYWORD_DATA,
            DocumentInterface::KEYWORD_ERRORS,
            DocumentInterface::KEYWORD_META
        ])) {
            return parent::__call($name, $argument = $arguments[0] ?? null);
        }

        return parent::__call($name, $arguments);
    }
}
