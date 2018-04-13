<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use Exception;
use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\ErrorsMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\IncludedMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\JsonApiMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\MetaMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\ResourceMapperInterface;
use FreddieGar\JsonApiMapper\Mappers\ResourceMapper;
use FreddieGar\JsonApiMapper\Tests\TestCase;

class ResourceMapperTest extends TestCase
{
    /**
     * @param null $input
     * @return ResourceMapperInterface
     */
    protected function resourceMapper($input = null)
    {
        return new ResourceMapper($input);
    }

    protected function runTestResourceInstance(ResourceMapperInterface $resource)
    {
        $this->assertEquals(null, $resource->getData());
        $this->assertEquals(null, $resource->getErrors());
        $this->assertEquals(null, $resource->getMeta());
        $this->assertEquals(null, $resource->getJsonApi());
        $this->assertEquals(null, $resource->getLinks());
        $this->assertEquals(null, $resource->getIncluded());
    }

    public function testResourceMapperInvalid()
    {
        foreach (['', null, false, true, 'data', []] as $input) {
            try {
                $this->resourceMapper($input);
                $this->assertTrue(false);
            } catch (Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testResourceMapperFromConstructor()
    {
        $resource = $this->resourceMapper('{}');

        $this->runTestResourceInstance($resource);
    }

    public function testResourceMapperFromLoad()
    {
        $resource = $this->resourceMapper()->load('{}');

        $this->runTestResourceInstance($resource);

    }

    public function testResourceMapperDataSimpleOk()
    {
        $resource = $this->resourceMapper($this->instanceDataSimple());

        $this->assertInstanceOf(DataMapperInterface::class, $resource->getData());
    }

    public function testResourceMapperDataMultipleOk()
    {
        $resource = $this->resourceMapper($this->instanceDataMultiple());

        $this->assertEquals(4, $resource->getData()->count());
        $this->assertInstanceOf(DataMapperInterface::class, $resource->getData());
        $this->assertInstanceOf(DataMapperInterface::class, $resource->getData(0));
        $this->assertInstanceOf(DataMapperInterface::class, $resource->getData(1));
        $this->assertInstanceOf(DataMapperInterface::class, $resource->getData(2));
        $this->assertInstanceOf(DataMapperInterface::class, $resource->getData(3));
        $this->assertEquals(null, $resource->getData(4));
    }

    public function testResourceMapperErrorsSimpleOk()
    {
        $resource = $this->resourceMapper($this->instanceErrorsSimple());

        $this->assertInstanceOf(ErrorsMapperInterface::class, $resource->getErrors());
    }

    public function testResourceMapperErrorsMultipleOk()
    {
        $resource = $this->resourceMapper($this->instanceErrorsMultiple());

        $this->assertEquals(3, $resource->getErrors()->count());
        $this->assertInstanceOf(ErrorsMapperInterface::class, $resource->getErrors());
        $this->assertInstanceOf(ErrorsMapperInterface::class, $resource->getErrors(0));
        $this->assertInstanceOf(ErrorsMapperInterface::class, $resource->getErrors(1));
        $this->assertInstanceOf(ErrorsMapperInterface::class, $resource->getErrors(2));
        $this->assertEquals(null, $resource->getErrors(3));
    }

    public function testResourceMapperMetaOk()
    {
        $resource = $this->resourceMapper($this->instanceMeta());

        $this->assertInstanceOf(MetaMapperInterface::class, $resource->getMeta());
    }

    public function testResourceMapperJsonApiOk()
    {
        $resource = $this->resourceMapper($this->instanceJsonApi());

        $this->assertInstanceOf(JsonApiMapperInterface::class, $resource->getJsonApi());
    }

    public function testResourceLinksOk()
    {
        $resource = $this->resourceMapper($this->instanceLinks());

        $this->assertInstanceOf(LinksMapperInterface::class, $resource->getLinks());
    }

    public function testResourceIncludedOk()
    {
        $resource = $this->resourceMapper($this->instanceDataWithIncluded());

        $this->assertInstanceOf(IncludedMapperInterface::class, $resource->getIncluded());
    }
}