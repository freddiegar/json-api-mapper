<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use Exception;
use FreddieGar\JsonApiMapper\Contracts\ErrorsMapperInterface;
use FreddieGar\JsonApiMapper\Mappers\ErrorsMapper;
use FreddieGar\JsonApiMapper\Tests\TestCase;

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

    private function runTestOn(array $data)
    {
        /**
         * @var $errorId
         * @var $errorAbout
         * @var $errorStatus
         * @var $errorCode
         * @var $errorTitle
         * @var $errorDetail
         * @var $errorSource
         * @var $errorMeta
         * @var $errorMetaCopyright
         * @var $errorMetaIsTest
         * @var $errorMetaNull
         */
        extract($data);

        $this->assertEquals(3452435234, $errorId);
        $this->assertEquals('http://example.com/help/me', $errorAbout);
        $this->assertEquals(422, $errorStatus);
        $this->assertEquals('001', $errorCode);
        $this->assertEquals('Invalid Attribute', $errorTitle);
        $this->assertEquals('First name must contain at least three characters.', $errorDetail);
        $this->assertTrue(is_array($errorSource));
        $this->assertTrue(is_array($errorMeta));
        $this->assertEquals('Copyright 2015 Example Corp.', $errorMetaCopyright);
        $this->assertEquals('no', $errorMetaIsTest);
        $this->assertEquals(null, $errorMetaNull);
    }

    public function testErrorsMapperSimpleGet()
    {
        $errors = $this->errorsMapper($this->instanceErrorsSimple());
        $error = $errors->get(0);

        $errorId = $error->getId();
        $errorAbout = $error->getAbout();
        $errorStatus = $error->getStatus();
        $errorCode = $error->getCode();
        $errorTitle = $error->getTitle();
        $errorDetail = $error->getDetail();
        $errorSource = $error->getSource();
        $errorMeta = $error->getMeta();
        $errorMetaCopyright = $error->getMeta('copyright');
        $errorMetaIsTest = $error->getMeta('is-test');
        $errorMetaNull = $error->getMeta('invalid');

        $this->runTestOn(get_defined_vars());
    }

    public function testErrorsMapperSimpleAlias()
    {
        $errors = $this->errorsMapper($this->instanceErrorsSimple());
        $error = $errors->get(0);

        $errorId = $error->id();
        $errorAbout = $error->about();
        $errorStatus = $error->status();
        $errorCode = $error->code();
        $errorTitle = $error->title();
        $errorDetail = $error->detail();
        $errorSource = $error->source();
        $errorMeta = $error->meta();
        $errorMetaCopyright = $error->meta('copyright');
        $errorMetaIsTest = $error->meta('is-test');
        $errorMetaNull = $error->meta('invalid');

        $this->runTestOn(get_defined_vars());
    }

    public function testErrorsMapperPropertySnakeAccessors()
    {
        $errors = $this->errorsMapper($this->instanceErrorsSimple());
        $error = $errors->get(0);

        $errorId = $error->id;
        $errorAbout = $error->about;
        $errorStatus = $error->status;
        $errorCode = $error->code;
        $errorTitle = $error->title;
        $errorDetail = $error->detail;
        $errorSource = $error->source;
//        $errorMeta = $error->meta;
//        $errorMetaCopyright = $error->meta->copyright;
//        $errorMetaIsTest = $error->meta->is_test;
//        $errorMetaNull = $error->invalid;
        $errorMeta = $error->meta();
        $errorMetaCopyright = $error->meta('copyright');
        $errorMetaIsTest = $error->meta('is-test');
        $errorMetaNull = $error->meta('invalid');

        $this->runTestOn(get_defined_vars());
    }

    public function testErrorsMapperPropertyCamelAccessors()
    {
        $errors = $this->errorsMapper($this->instanceErrorsSimple());
        $error = $errors->get(0);

        $errorId = $error->id;
        $errorAbout = $error->about;
        $errorStatus = $error->status;
        $errorCode = $error->code;
        $errorTitle = $error->title;
        $errorDetail = $error->detail;
        $errorSource = $error->source;
//        $errorMeta = $error->meta;
//        $errorMetaCopyright = $error->meta->copyright;
//        $errorMetaIsTest = $error->meta->isTest;
//        $errorMetaNull = $error->invalid;
        $errorMeta = $error->meta();
        $errorMetaCopyright = $error->meta('copyright');
        $errorMetaIsTest = $error->meta('is-test');
        $errorMetaNull = $error->meta('invalid');

        $this->runTestOn(get_defined_vars());
    }

    public function testErrorsMapperMultiple()
    {
        $errors = $this->errorsMapper($this->instanceErrorsMultiple());
        $count = $errors->count();
        $this->assertEquals(3, $count);
        $this->assertTrue(is_array($errors->all()));

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