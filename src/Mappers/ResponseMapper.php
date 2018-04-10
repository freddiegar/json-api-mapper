<?php

namespace PlacetoPay\JsonApiMapper\Mappers;

use Exception;
use InvalidArgumentException;
use PlacetoPay\JsonApiMapper\Contracts\DataMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\ErrorsMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\IncludedMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\JsonApiMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\LinksMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\MetaMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\ResponseMapperInterface;

/**
 * Class ResponseMapper
 * @package PlacetoPay\JsonApiMapper\Mappers
 */
class ResponseMapper extends LoaderMapper implements ResponseMapperInterface
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
     * @var JsonApiMapperInterface
     */
    private $jsonApiMapper;

    /**
     * @var IncludedMapperInterface
     */
    private $includedMapper;

    public function load($input, ?string $tag = null)
    {
        if(is_null($input)){
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
        $this->jsonApiMapper = new JsonApiMapper($this->response);
        $this->linksMapper = new LinksMapper($this->response);
        $this->includedMapper = new IncludedMapper($this->response);

        return $this;
    }

    public function getData(?int $index = null): ?DataMapperInterface
    {
        return $this->dataMapper->get($index);
    }

    public function getErrors(?int $index = null): ?ErrorsMapperInterface
    {
        return $this->errorsMapper->get($index);
    }

    public function getMeta(): MetaMapperInterface
    {
        return $this->metaMapper->get();
    }

    public function getJsonApi(): JsonApiMapperInterface
    {
        return $this->jsonApiMapper->get();
    }

    public function getLinks(): LinksMapperInterface
    {
        return $this->linksMapper->get();
    }

    public function getIncluded(): IncludedMapperInterface
    {
        return $this->includedMapper->get();
    }
}
