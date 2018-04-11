<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use Exception;
use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Mappers\DataMapper;
use FreddieGar\JsonApiMapper\Tests\TestCase;

class DataMapperTest extends TestCase
{
    /**
     * @param null $input
     * @return DataMapper
     */
    protected function dataMapper($input = null)
    {
        return new DataMapper($input);
    }

    public function testDataMapperInvalid()
    {
        foreach (['', null, false, true, 'data', []] as $input) {
            try {
                $this->dataMapper($input);
                $this->assertTrue(false);
            } catch (Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testDataMapperFromConstructor()
    {
        $data = $this->dataMapper('{}');

        $this->assertInstanceOf(DataMapperInterface::class, $data);
    }

    public function testDataMapperFromLoad()
    {
        $data = $this->dataMapper()->load('{}');

        $this->assertInstanceOf(DataMapperInterface::class, $data);
    }

    public function testDataMapperSimpleOk()
    {
        $data = $this->dataMapper($this->instanceDataSimple());

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
        $this->assertTrue(is_array($data->getLinks()->getRelated()));

        $this->assertEquals(null, $data->getAttribute('attribute-invalid'));
        $this->assertEquals(null, $data->getRelationship('relationship-invalid'));
    }

    public function testDataMapperMultipleOk()
    {
        $dataMultiple = $this->dataMapper($this->instanceDataMultiple());
        $count = $dataMultiple->count();
        $this->assertEquals(4, $count);

        for ($i = 0; $i < $count; ++$i) {
            $data = $dataMultiple->get($i);
            $this->assertEquals('users', $data->getType());

            switch ($data->getId()) {
                case 1:
                    $this->assertEquals('Jon Doe', $data->getAttribute('name'));
                    $this->assertEquals('es', $data->getAttribute('language-id'));
                    $this->assertEquals(null, $data->getAttribute('description'));
                    $this->assertEquals('2018-02-14T16:03:43.000Z', $data->getAttribute('created-at'));
                    $this->assertEquals('2018-02-14T17:05:35.000Z', $data->getAttribute('updated-at'));
                    break;
                case 2:
                    $this->assertEquals('Sam Doe', $data->getAttribute('name'));
                    $this->assertEquals('es', $data->getAttribute('language-id'));
                    $this->assertEquals('Un-know', $data->getAttribute('description'));
                    $this->assertEquals('2018-03-14T06:13:55.000Z', $data->getAttribute('created-at'));
                    $this->assertEquals(null, $data->getAttribute('updated-at'));
                    break;
                case 3:
                    $this->assertEquals('Steve Jobs', $data->getAttribute('name'));
                    $this->assertEquals('es', $data->getAttribute('language-id'));
                    $this->assertEquals('Engineer', $data->getAttribute('description'));
                    $this->assertEquals('2017-12-24T10:53:43.000Z', $data->getAttribute('created-at'));
                    $this->assertEquals(null, $data->getAttribute('updated-at'));
                    break;
                case 4:
                    $this->assertEquals('', $data->getAttribute('name'));
                    $this->assertEquals('es', $data->getAttribute('language-id'));
                    $this->assertEquals('Literature', $data->getAttribute('description'));
                    $this->assertEquals('2008-02-11T01:01:41.000Z', $data->getAttribute('created-at'));
                    $this->assertEquals(null, $data->getAttribute('updated-at'));
                    break;
                default:
                    $this->assertTrue(false, 'Register of [data] not expected');
            }
        }
    }

    public function testDataMapperSimpleEmpty()
    {
        $data = $this->dataMapper('{"data": null}');
        $this->assertInstanceOf(DataMapperInterface::class, $data);
        $this->assertEquals(null, $data->get());
        $this->assertEquals(null, $data->get(0));
    }

    public function testDataMapperMultipleEmpty()
    {
        $dataMultiple = $this->dataMapper('{"data": []}');
        $count = $dataMultiple->count();
        $this->assertInstanceOf(DataMapperInterface::class, $dataMultiple);
        $this->assertEquals(0, $count);
        $this->assertEquals([], $dataMultiple->get());
        $this->assertEquals(null, $dataMultiple->get(0));
    }
}