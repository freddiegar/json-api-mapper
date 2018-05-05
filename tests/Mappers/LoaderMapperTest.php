<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\RelatedMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\ResourceMapperInterface;
use FreddieGar\JsonApiMapper\JsonApiMapper;
use FreddieGar\JsonApiMapper\Mappers\DataMapper;
use FreddieGar\JsonApiMapper\Mappers\ResourceMapper;
use FreddieGar\JsonApiMapper\Tests\TestCase;

class LoaderMapperTest extends TestCase
{
    private function getDataHow($type)
    {
        $data = $this->instanceDataSimple();

        switch ($type) {
            case 'object':
                $data = json_decode($data);
                break;
            case 'array':
                $data = json_decode($data, true);
                break;
            case 'string':
                $data = (string)$data;
                break;
            default:
                $this->assertTrue(false, sprintf('Type [%S] is not supported'));
        }

        return $data;
    }

    /**
     * @param DataMapperInterface $data
     */
    private function runTestOn($data)
    {
        $this->assertEquals('users', $data->getType());
        $this->assertEquals(1449216560, $data->getId());
        $this->assertTrue(is_array($data->getAttributes()));
        $this->assertTrue(is_array($data->getRelationships()));
        $this->assertEquals('Jon Doe', $data->getAttribute('name'));
        $this->assertEquals('es', $data->getAttribute('language-id'));
        $this->assertEquals(null, $data->getAttribute('description'));
        $this->assertEquals('2018-02-14T16:03:43.000Z', $data->getAttribute('created-at'));
        $this->assertEquals('2018-02-14T17:05:35.000Z', $data->getAttribute('updated-at'));
        $this->assertInstanceOf(DataMapperInterface::class, $data->getRelationship('language'));
        $this->assertInstanceOf(LinksMapperInterface::class, $data->getLinks());
        $this->assertEquals('http://example.com/posts/1449216560', $data->getLinks()->getSelf());
        $this->assertInstanceOf(RelatedMapperInterface::class, $data->getLinks()->getRelated());
        $this->assertEquals('http://example.com/posts/1449216560/comments', $data->getLinks()->getRelated()->getHref());
        $this->assertTrue(is_array($data->getLinks()->getRelated()->getMeta()));
        $this->assertEquals(10, $data->getLinks()->getRelated()->getMeta('count'));

        $this->assertEquals(null, $data->getAttribute('attribute-invalid'));
        $this->assertEquals(null, $data->getRelationship('relationship-invalid'));
    }

    public function testLoaderMapperFromConstructor()
    {
        $loader = new JsonApiMapper('{}');

        $this->assertInstanceOf(ResourceMapperInterface::class, $loader);
    }

    public function testLoaderMapperFromLoad()
    {
        $loader = (new JsonApiMapper())->load('{}');

        $this->assertInstanceOf(ResourceMapperInterface::class, $loader);
    }


    public function testLoaderMapperWithString()
    {
        $loader = new JsonApiMapper($this->getDataHow('string'));

        $this->assertInstanceOf(ResourceMapperInterface::class, $loader);
        $this->runTestOn($loader->getData());
    }

    public function testLoaderMapperWithObject()
    {
        $loader = new JsonApiMapper($this->getDataHow('object'));

        $this->assertInstanceOf(ResourceMapperInterface::class, $loader);
        $this->runTestOn($loader->getData());
    }

    public function testLoaderMapperWithArray()
    {
        $loader = new JsonApiMapper($this->getDataHow('array'));

        $this->assertInstanceOf(ResourceMapperInterface::class, $loader);
        $this->runTestOn($loader->getData());
    }

    public function testLoaderMapperResponseWithString()
    {
        $loader = new ResourceMapper($this->getDataHow('string'));

        $this->assertInstanceOf(ResourceMapperInterface::class, $loader);
        $this->runTestOn($loader->getData());
    }

    public function testLoaderMapperResponseWithObject()
    {
        $loader = new ResourceMapper($this->getDataHow('object'));

        $this->assertInstanceOf(ResourceMapperInterface::class, $loader);
        $this->runTestOn($loader->getData());
    }

    public function testLoaderMapperResponseWithArray()
    {
        $loader = new ResourceMapper($this->getDataHow('array'));

        $this->assertInstanceOf(ResourceMapperInterface::class, $loader);
        $this->runTestOn($loader->getData());
    }

    public function testLoaderMapperDataWithString()
    {
        $loader = new DataMapper($this->getDataHow('string'));

        $this->assertInstanceOf(DataMapperInterface::class, $loader);
        $this->runTestOn($loader);
    }

    public function testLoaderMapperDataWithObject()
    {
        $loader = new DataMapper($this->getDataHow('object'));

        $this->assertInstanceOf(DataMapperInterface::class, $loader);
        $this->runTestOn($loader);
    }

    public function testLoaderMapperDataWithArray()
    {
        $loader = new DataMapper($this->getDataHow('array'));

        $this->assertInstanceOf(DataMapperInterface::class, $loader);
        $this->runTestOn($loader);
    }
}
