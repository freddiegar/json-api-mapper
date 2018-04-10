<?php

namespace PlacetoPay\JsonApiMapper\Tests\Mappers;

use Exception;
use PlacetoPay\JsonApiMapper\Contracts\DataMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\ErrorsMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\IncludedMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\JsonApiMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\LinksMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\MetaMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\ResponseMapperInterface;
use PlacetoPay\JsonApiMapper\Mappers\ResponseMapper;
use PlacetoPay\JsonApiMapper\Tests\TestCase;

class ResponseMapperTest extends TestCase
{
    /**
     * @param null $input
     * @return ResponseMapperInterface
     */
    protected function responseMapper($input = null)
    {
        return new ResponseMapper($input);
    }

    protected function runTestResponseInstance(ResponseMapperInterface $response)
    {
        $this->assertInstanceOf(DataMapperInterface::class, $response->getData());
        $this->assertInstanceOf(ErrorsMapperInterface::class, $response->getErrors());
        $this->assertInstanceOf(MetaMapperInterface::class, $response->getMeta());
        $this->assertInstanceOf(LinksMapperInterface::class, $response->getlinks());
    }

    public function testResponseMapperInvalid()
    {
        foreach (['', null, false, true, 'data', []] as $input) {
            try {
                $this->responseMapper($input);
                $this->assertTrue(false);
            } catch (Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testResponseMapperFromConstructor()
    {
        $response = $this->responseMapper('{}');

        $this->runTestResponseInstance($response);
    }

    public function testResponseMapperFromLoad()
    {
        $response = $this->responseMapper()->load('{}');

        $this->runTestResponseInstance($response);

    }

    public function testResponseMapperDataSimpleOk()
    {
        $response = $this->responseMapper($this->instanceDataSimple());

        $this->assertInstanceOf(DataMapperInterface::class, $response->getData());
    }

    public function testResponseMapperDataMultipleOk()
    {
        $response = $this->responseMapper($this->instanceDataMultiple());

        $this->assertEquals(4, $response->getData()->count());
        $this->assertInstanceOf(DataMapperInterface::class, $response->getData());
        $this->assertInstanceOf(DataMapperInterface::class, $response->getData(0));
        $this->assertInstanceOf(DataMapperInterface::class, $response->getData(1));
        $this->assertInstanceOf(DataMapperInterface::class, $response->getData(2));
        $this->assertInstanceOf(DataMapperInterface::class, $response->getData(3));
        $this->assertEquals(null, $response->getData(4));
    }

    public function testResponseMapperErrorsSimpleOk()
    {
        $response = $this->responseMapper($this->instanceErrorsSimple());

        $this->assertInstanceOf(ErrorsMapperInterface::class, $response->getErrors());
    }

    public function testResponseMapperErrorsMultipleOk()
    {
        $response = $this->responseMapper($this->instanceErrorsMultiple());

        $this->assertEquals(3, $response->getErrors()->count());
        $this->assertInstanceOf(ErrorsMapperInterface::class, $response->getErrors());
        $this->assertInstanceOf(ErrorsMapperInterface::class, $response->getErrors(0));
        $this->assertInstanceOf(ErrorsMapperInterface::class, $response->getErrors(1));
        $this->assertInstanceOf(ErrorsMapperInterface::class, $response->getErrors(2));
        $this->assertEquals(null, $response->getErrors(3));
    }

    public function testResponseMapperMetaOk()
    {
        $response = $this->responseMapper($this->instanceMeta());

        $this->assertInstanceOf(MetaMapperInterface::class, $response->getMeta());
    }

    public function testResponseMapperJsonApiOk()
    {
        $response = $this->responseMapper($this->instanceJsonApi());

        $this->assertInstanceOf(JsonApiMapperInterface::class, $response->getJsonApi());
    }

    public function testResponseLinksOk()
    {
        $response = $this->responseMapper($this->instanceLinks());

        $this->assertInstanceOf(LinksMapperInterface::class, $response->getLinks());
    }

    public function testResponseIncludedOk()
    {
        $response = $this->responseMapper($this->instanceDataWithIncluded());

        $this->assertInstanceOf(IncludedMapperInterface::class, $response->getIncluded());
    }
}