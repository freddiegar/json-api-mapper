<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use Exception;
use FreddieGar\JsonApiMapper\Contracts\ObjectJsonApiMapperInterface;
use FreddieGar\JsonApiMapper\Mappers\ObjectJsonApiMapper;
use FreddieGar\JsonApiMapper\Tests\TestCase;

class ObjectJsonApiMapperTest extends TestCase
{
    /**
     * @param null $input
     * @return ObjectJsonApiMapperInterface
     */
    protected function jsonApiMapper($input = null)
    {
        return new ObjectJsonApiMapper($input);
    }

    public function testObjectJsonApiMapperInvalid()
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

    public function testObjectJsonApiMapperFromConstructor()
    {
        $jsonApi = $this->jsonApiMapper('{}');

        $this->assertInstanceOf(ObjectJsonApiMapperInterface::class, $jsonApi);
    }

    public function testObjectJsonApiMapperFromLoad()
    {
        $jsonApi = $this->jsonApiMapper()->load('{}');

        $this->assertInstanceOf(ObjectJsonApiMapperInterface::class, $jsonApi);
    }

    public function testObjectJsonApiMapperSimpleOk()
    {
        $meta = $this->jsonApiMapper($this->instanceJsonApi());

        $this->assertEquals('1.0', $meta->getVersion());
    }

    public function testObjectJsonApiMapperSimpleBad()
    {
        $meta = $this->jsonApiMapper('{}');

        $this->assertEquals(null, $meta->getVersion());
    }
}