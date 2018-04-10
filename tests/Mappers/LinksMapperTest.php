<?php

namespace PlacetoPay\JsonApiMapper\Tests\Mappers;

use Exception;
use PlacetoPay\JsonApiMapper\Contracts\LinksMapperInterface;
use PlacetoPay\JsonApiMapper\Mappers\LinksMapper;
use PlacetoPay\JsonApiMapper\Tests\TestCase;

class LinksMapperTest extends TestCase
{
    private function runTestOn(LinksMapperInterface $links)
    {
        $this->assertInstanceOf(LinksMapperInterface::class, $links);
        $this->assertEquals(null, $links->getSelf());
        $this->assertEquals(null, $links->getAbout());
        $this->assertEquals(null, $links->getRelated());
        $this->assertEquals(null, $links->getFirst());
        $this->assertEquals(null, $links->getPrev());
        $this->assertEquals(null, $links->getNext());
        $this->assertEquals(null, $links->getLast());
    }

    /**
     * @param null $input
     * @return LinksMapperInterface
     */
    protected function linksMapper($input = null)
    {
        return new LinksMapper($input);
    }

    public function testLinksMapperInvalid()
    {
        foreach (['', null, false, true, 'data', []] as $input) {
            try {
                $this->linksMapper($input);
                $this->assertTrue(false);
            } catch (Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testLinksMapperFromConstructor()
    {
        $links = $this->linksMapper('{}');

        $this->runTestOn($links);
    }

    public function testLinksMapperFromLoad()
    {
        $links = $this->linksMapper()->load('{}');

        $this->runTestOn($links);
    }

    public function testLinksMapperSimpleOk()
    {
        $links = $this->linksMapper($this->instanceLinks())->get();

        $this->assertEquals('http://example.com/posts', $links->getSelf());
        $this->assertEquals('http://example.com/articles?page[number]=1&page[size]=1', $links->getFirst());
        $this->assertEquals('http://example.com/articles?page[number]=2&page[size]=1', $links->getPrev());
        $this->assertEquals('http://example.com/articles?page[number]=4&page[size]=1', $links->getNext());
        $this->assertEquals('http://example.com/articles?page[number]=13&page[size]=1', $links->getLast());
        $this->assertTrue(is_array($links->getRelated()));
        $this->assertEquals('http://example.com/articles/1/comments', $links->getRelated('href'));
        $this->assertTrue(is_array($links->getRelated('meta')));
        $this->assertEquals(10, $links->getRelated('meta.count'));

        $this->assertEquals(null, $links->getRelated('notExists'));
    }
}