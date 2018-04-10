<?php

namespace PlacetoPay\JsonApiMapper\Tests\Mappers;

use Exception;
use PlacetoPay\JsonApiMapper\Contracts\ErrorsMapperInterface;
use PlacetoPay\JsonApiMapper\Mappers\ErrorsMapper;
use PlacetoPay\JsonApiMapper\Tests\TestCase;

class ErrorsMapperTest extends TestCase
{
    /**
     * @param null $input
     * @return ErrorsMapperInterface
     */
    protected function errorsMapper($input = null)
    {
        return new ErrorsMapper($input);
    }

    public function testErrorsMapperInvalid()
    {
        foreach (['', null, false, true, 'data', []] as $input) {
            try {
                $this->errorsMapper($input);
                $this->assertTrue(false);
            } catch (Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testErrorsMapperFromConstructor()
    {
        $errors = $this->errorsMapper('{}');

        $this->assertInstanceOf(ErrorsMapperInterface::class, $errors);
    }

    public function testErrorsMapperFromLoad()
    {
        $errors = $this->errorsMapper()->load('{}');

        $this->assertInstanceOf(ErrorsMapperInterface::class, $errors);
    }

    public function testErrorsMapperSimpleOk()
    {
        $errors = $this->errorsMapper($this->instanceErrorsSimple());
        $error = $errors->get(0);

        $this->assertEquals(3452435234, $error->getId());
        $this->assertEquals('http://example.com/help/me', $error->getAbout());
        $this->assertEquals(422, $error->getStatus());
        $this->assertEquals('001', $error->getCode());
        $this->assertEquals('Invalid Attribute', $error->getTitle());
        $this->assertEquals('First name must contain at least three characters.', $error->getDetail());
        $this->assertTrue(is_array($error->getSource()));
        $this->assertTrue(is_array($error->getMeta()));
        $this->assertEquals('Copyright 2015 Example Corp.', $error->getMeta('copyright'));
    }

    public function testErrorsMapperMultipleOk()
    {
        $errors = $this->errorsMapper($this->instanceErrorsMultiple());
        $count = $errors->count();
        $this->assertEquals(3, $count);

        for ($i = 0; $i < $count; ++$i) {
            $error = $errors->get($i);

            switch ($i) {
                case 0:
                    $this->assertEquals(null, $error->getId());
                    $this->assertEquals(403, $error->getStatus());
                    $this->assertEquals('Editing secret powers is not authorized on Sundays.', $error->getDetail());
                    $this->assertTrue(is_array($error->getSource()));
                    break;
                case 1:
                    $this->assertEquals('002', $error->getCode());
                    $this->assertEquals(422, $error->getStatus());
                    $this->assertEquals('Volume does not, in fact, go to 11.', $error->getDetail());
                    $this->assertTrue(is_array($error->getSource()));
                    break;
                case 2:
                    $this->assertEquals(500, $error->getStatus());
                    $this->assertEquals('The backend responded with an error', $error->getTitle());
                    $this->assertEquals('Reputation service not responding after three requests.', $error->getDetail());
                    $this->assertTrue(is_array($error->getSource()));
                    break;
                default:
                    $this->assertTrue(false, 'Register of [error] not expected');
            }
        }
    }
}