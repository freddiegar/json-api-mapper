<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use Exception;
use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\IncludedMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Mappers\IncludedMapper;
use FreddieGar\JsonApiMapper\Tests\TestCase;

class IncludedMapperTest extends TestCase
{
    /**
     * @param null $input
     * @return IncludedMapperInterface
     */
    protected function includedMapper($input = null)
    {
        return new IncludedMapper($input);
    }

    public function testIncludedMapperInvalid()
    {
        foreach (['', null, false, true, 'data', []] as $input) {
            try {
                $this->includedMapper($input);
                $this->assertTrue(false);
            } catch (Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testIncludedMapperFromConstructor()
    {
        $included = $this->includedMapper('{}');

        $this->assertInstanceOf(IncludedMapperInterface::class, $included);
    }

    public function testIncludedMapperFromLoad()
    {
        $included = $this->includedMapper()->load('{}');

        $this->assertInstanceOf(IncludedMapperInterface::class, $included);
    }

    public function testIncludedMapperSimpleGet()
    {
        $included = $this->includedMapper($this->instanceDataWithIncluded());

        $dataPeople = $included->find('people');
        $dataComments = $included->find('comments');
        $dataNotValid = $included->find('');

        $this->assertEquals(3, $included->count());
        $this->assertEquals(1, $dataPeople->count());
        $this->assertEquals(2, $dataComments->count());
        $this->assertEquals(null, $dataNotValid);

        $this->assertInstanceOf(DataMapperInterface::class, $dataPeople->find(9));
        $this->assertEquals(null, $dataPeople->find(10));

        $this->assertInstanceOf(DataMapperInterface::class, $dataComments->find(5));
        $this->assertInstanceOf(DataMapperInterface::class, $dataComments->find(12));
        $this->assertEquals(null, $dataComments->find(13));

        $data = $included->getIncluded(0);
        $this->assertInstanceOf(DataMapperInterface::class, $data);
        $this->assertEquals('people', $data->getType());
        $this->assertEquals(9, $data->getId());
        $this->assertTrue(is_array($data->getAttributes()));
        $this->assertEquals('Dan', $data->getAttribute('first-name'));
        $this->assertEquals('Yamaha', $data->getAttribute('last-name'));
        $this->assertEquals('daniel', $data->getAttribute('twitter'));
        $this->assertInstanceOf(LinksMapperInterface::class, $data->getLinks());
        $this->assertEquals('http://example.com/people/9', $data->getLinks()->getSelf());
        $this->assertEquals(null, $data->getLinks()->getRelated());

        $this->assertEquals(null, $data->getAttribute('attribute-invalid'));
        $this->assertEquals(null, $data->getRelationship('relationship-invalid'));

        $data = $included->getIncluded(1);
        $this->assertInstanceOf(DataMapperInterface::class, $data);
        $this->assertEquals('comments', $data->getType());
        $this->assertEquals(5, $data->getId());
        $this->assertTrue(is_array($data->getAttributes()));
        $this->assertEquals('First!', $data->getAttribute('body'));
        $this->assertTrue(is_array($data->getRelationships()));
        $this->assertInstanceOf(DataMapperInterface::class, $data->getRelationship('author'));
        $this->assertEquals('people', $data->getRelationship('author')->getType());
        $this->assertEquals(2, $data->getRelationship('author')->getId());
        $this->assertInstanceOf(LinksMapperInterface::class, $data->getLinks());
        $this->assertEquals('http://example.com/comments/5', $data->getLinks()->getSelf());

        $data = $included->getIncluded(2);
        $this->assertInstanceOf(DataMapperInterface::class, $data);
        $this->assertEquals('comments', $data->getType());
        $this->assertEquals(12, $data->getId());
        $this->assertTrue(is_array($data->getAttributes()));
        $this->assertEquals('I like XML better', $data->getAttribute('body'));
        $this->assertTrue(is_array($data->getRelationships()));
        $this->assertInstanceOf(DataMapperInterface::class, $data->getRelationship('author'));
        $this->assertEquals('people', $data->getRelationship('author')->getType());
        $this->assertEquals(9, $data->getRelationship('author')->getId());
        $this->assertInstanceOf(LinksMapperInterface::class, $data->getLinks());
        $this->assertEquals('http://example.com/comments/12', $data->getLinks()->getSelf());

        $data = $included->getIncluded(3);
        $this->assertEquals(null, $data);
    }
}