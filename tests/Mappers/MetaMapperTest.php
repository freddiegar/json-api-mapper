<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use Exception;
use FreddieGar\JsonApiMapper\Contracts\MetaMapperInterface;
use FreddieGar\JsonApiMapper\Mappers\MetaMapper;
use FreddieGar\JsonApiMapper\Tests\TestCase;

class MetaMapperTest extends TestCase
{
    /**
     * @param null $input
     * @return MetaMapperInterface
     */
    protected function metaMapper($input = null)
    {
        return new MetaMapper($input);
    }

    public function testMetaMapperInvalid()
    {
        foreach (['', null, false, true, 'data', []] as $input) {
            try {
                $this->metaMapper($input);
                $this->assertTrue(false);
            } catch (Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testMetaMapperFromConstructor()
    {
        $meta = $this->metaMapper('{}');

        $this->assertInstanceOf(MetaMapperInterface::class, $meta);
    }

    public function testMetaMapperFromLoad()
    {
        $meta = $this->metaMapper()->load('{}');

        $this->assertInstanceOf(MetaMapperInterface::class, $meta);
    }

    public function testMetaMapperSimpleOk()
    {
        $meta = $this->metaMapper($this->instanceMeta());

        $this->assertInstanceOf(MetaMapperInterface::class, $meta);
        $this->assertEquals('Copyright 2015 Example Corp.', $meta->getMeta('copyright'));
        $this->assertTrue(is_array($meta->getMeta('authors')));
        $this->assertEquals('Frank Kafka', $meta->getMeta('authors.0'));
        $this->assertEquals('Steve Apache', $meta->getMeta('authors.1'));
        $this->assertEquals('Dan Yamaha', $meta->getMeta('authors.2'));
        $this->assertEquals('Tyler Kellen', $meta->getMeta('authors.3'));

        $this->assertEquals(null, $meta->getMeta('authors.4'));
        $this->assertEquals(null, $meta->getMeta('notExists'));
    }
}
