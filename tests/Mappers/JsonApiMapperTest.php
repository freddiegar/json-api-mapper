<?php

namespace PlacetoPay\JsonApiMapper\Tests\Mappers;

use Exception;
use PlacetoPay\JsonApiMapper\Contracts\JsonApiMapperInterface;
use PlacetoPay\JsonApiMapper\Mappers\JsonApiMapper;
use PlacetoPay\JsonApiMapper\Tests\TestCase;

class JsonApiMapperTest extends TestCase
{
    /**
     * @param null $input
     * @return JsonApiMapperInterface
     */
    protected function jsonApiMapper($input = null)
    {
        return new JsonApiMapper($input);
    }

    public function testMetaMapperInvalid()
    {
        foreach (['', null, false, true, 'data', []] as $input) {
            try {
                $this->jsonApiMapper($input);
                $this->assertTrue(false);
            } catch (Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testMetaMapperFromConstructor()
    {
        $jsonApi = $this->jsonApiMapper('{}');

        $this->assertInstanceOf(JsonApiMapperInterface::class, $jsonApi);
    }

    public function testMetaMapperFromLoad()
    {
        $jsonApi = $this->jsonApiMapper()->load('{}');

        $this->assertInstanceOf(JsonApiMapperInterface::class, $jsonApi);
    }

    public function testMetaMapperSimpleOk()
    {
        $meta = $this->jsonApiMapper($this->instanceJsonApi());

        $this->assertEquals('1.0', $meta->getVersion());
    }

    public function testMetaMapperSimpleBad()
    {
        $meta = $this->jsonApiMapper('{}');

        $this->assertEquals(null, $meta->getVersion());
    }
}