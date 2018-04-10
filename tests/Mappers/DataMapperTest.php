<?php

namespace PlacetoPay\JsonApiMapper\Tests\Mappers;

use Exception;
use PlacetoPay\JsonApiMapper\Contracts\DataMapperInterface;
use PlacetoPay\JsonApiMapper\Contracts\LinksMapperInterface;
use PlacetoPay\JsonApiMapper\Mappers\DataMapper;
use PlacetoPay\JsonApiMapper\Tests\TestCase;

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
        $this->assertEquals('http://example.com/posts/1449216560', $data->getLinkSelf());
        $this->assertTrue(is_array($data->getLinkRelated()));

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
}