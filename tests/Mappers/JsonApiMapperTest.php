<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use Exception;
use FreddieGar\JsonApiMapper\Contracts\JsonApiMapperInterface;
use FreddieGar\JsonApiMapper\Mappers\JsonApiMapper;
use FreddieGar\JsonApiMapper\Tests\TestCase;

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

    public function testJsonApiMapperInvalid()
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

    public function testJsonApiMapperFromConstructor()
    {
        $jsonApi = $this->jsonApiMapper('{}');

        $this->assertInstanceOf(JsonApiMapperInterface::class, $jsonApi);
    }

    public function testJsonApiMapperFromLoad()
    {
        $jsonApi = $this->jsonApiMapper()->load('{}');

        $this->assertInstanceOf(JsonApiMapperInterface::class, $jsonApi);
    }

    public function testJsonApiMapperSimpleOk()
    {
        $meta = $this->jsonApiMapper($this->instanceJsonApi());

        $this->assertEquals('1.0', $meta->getVersion());
    }

    public function testJsonApiMapperSimpleBad()
    {
        $meta = $this->jsonApiMapper('{}');

        $this->assertEquals(null, $meta->getVersion());
    }
}