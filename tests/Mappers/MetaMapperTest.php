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

        $this->assertEquals('Copyright 2015 Example Corp.', $meta->getPath('copyright'));
        $this->assertTrue(is_array($meta->getPath('authors')));
        $this->assertEquals('Yehuda Katz', $meta->getPath('authors.0'));
        $this->assertEquals('Steve Klabnik', $meta->getPath('authors.1'));
        $this->assertEquals('Dan Gebhardt', $meta->getPath('authors.2'));
        $this->assertEquals('Tyler Kellen', $meta->getPath('authors.3'));

        $this->assertEquals(null, $meta->getPath('authors.4'));
        $this->assertEquals(null, $meta->getPath('notExists'));
    }
}