<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\IncludedMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\JsonApiMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\MetaMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\ResponseMapperInterface;
use FreddieGar\JsonApiMapper\Mappers\ResponseMapper;
use FreddieGar\JsonApiMapper\Tests\TestCase;

class ImplementationMapperTest extends TestCase
{
    private function dataTest()
    {
        return <<<JSON
{
    "meta":{
        "total-count":2
    },
    "jsonapi":{
        "version":"1.0"
    },
    "links":{
        "href":"http://example.com/articles?filter[body][like]=json",
        "first":"http://example.com/articles?page[number]=1&page[size]=1",
        "prev":"http://example.com/articles?page[number]=2&page[size]=1",
        "next":"http://example.com/articles?page[number]=4&page[size]=1",
        "last":"http://example.com/articles?page[number]=13&page[size]=1"
    },
    "data":[
        {
            "type":"articles",
            "id":"10",
            "attributes":{
                "title":"JSON API Rules",
                "body":"Another article.",
                "created":"2015-06-22T11:58:29.000Z",
                "updated-at":"2016-05-22T14:59:02.000Z"
            },
            "relationships":{
                "author":{
                    "data":{
                        "id":"4",
                        "type":"people"
                    }
                }
            },
            "links":{
                "self":"http://example.com/articles/10",
                "related":{
                    "href":"http://example.com/articles/10/comments",
                    "meta":{
                        "count":10
                    }
                }
            }
        },
        {
            "type":"articles",
            "id":"20",
            "attributes":{
                "title":"JSON API paints my bikeshed!",
                "body":"The shortest article. Ever.",
                "created":"2015-05-22T14:56:29.000Z",
                "updated-at":"2015-05-22T14:56:28.000Z"
            },
            "relationships":{
                "author":{
                    "data":{
                        "id":"42",
                        "type":"people"
                    }
                }
            },
            "links":{
                "self":"http://example.com/articles/20",
                "related":{
                    "href":"http://example.com/articles/20/comments",
                    "meta":{
                        "count":2
                    }
                }
            }
        },
        {
            "type":"articles",
            "id":"25",
            "attributes":{
                "title":"JSON API woow",
                "body":"The longest article. Ever.",
                "created":"2018-05-22T14:56:29.000Z",
                "updated-at":null
            },
            "relationships":{
                "author":{
                    "data":{
                        "id":"42",
                        "type":"people"
                    }
                }
            },
            "links":{
                "self":"http://example.com/articles/25",
                "related":{
                    "href":"http://example.com/articles/25/comments",
                    "meta":{
                        "count":0
                    }
                }
            }
        }
    ],
    "included":[
        {
            "type":"people",
            "id":"42",
            "attributes":{
                "name":"John",
                "age":80,
                "gender":"male"
            }
        },
        {
            "type":"people",
            "id":"4",
            "attributes":{
                "name":"Sam",
                "age":30,
                "gender":"female"
            }
        }
    ]
}
JSON;
    }

    public function testImplementationMapperMethodGet()
    {
        $mapper = new ResponseMapper($this->dataTest());

        $meta = $mapper->getMeta();
        $metaTotalCount = $meta->getMeta('total-count');
        $metaNull = $meta->getMeta('invalid');

        $jsonApi = $mapper->getJsonApi();
        $jsonApiVersion = $jsonApi->getVersion();

        $links = $mapper->getLinks();
        $linkFirst = $links->getFirst();
        $linkPrev = $links->getPrev();
        $linkNext = $links->getNext();
        $linkLast = $links->getLast();
        $linkSelf = $links->getSelf();
        $linkAbout = $links->getAbout();
        $linkHref = $links->getHref();
        $linkRelated = $links->getRelated();

        $data = $mapper->getData(0);
        $dataType = $data->getType();
        $dataId = $data->getId();
        $dataAttributes = $data->getAttributes();
        $dataTitle = $data->getAttribute('title');
        $dataBody = $data->getAttribute('body');
        $dataCreated = $data->getAttribute('created');
        $dataUpdated = $data->getAttribute('updated-at');
        $dataNull = $data->getAttribute('invalid');

        $author = $data->getRelationship('author');
        $authorType = $author->getType();
        $authorId = $author->getId();
        $authorAttributes = $author->getAttributes();
        $authorName = $author->getAttribute('name');
        $authorAge = $author->getAttribute('age');
        $authorGender = $author->getAttribute('gender');
        $authorNull = $author->getAttribute('invalid');

        $dataRelationshipNull = $data->getRelationship('invalid');

        $dataLinks = $data->getLinks();
        $dataLinkSelf = $dataLinks->getSelf();
        $dataLinkRelated = $dataLinks->getRelated();
        $dataLinkRelatedHref = $dataLinks->getRelated('href');
        $dataLinkRelatedMeta = $dataLinks->getRelated('meta');
        $dataLinkRelatedMetaCount = $dataLinks->getRelated('meta.count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->getType();
        $dataByIdId = $dataById->getId();
        $dataByIdAttributes = $dataById->getAttributes();
        $dataByIdTitle = $dataById->getAttribute('title');
        $dataByIdBody = $dataById->getAttribute('body');
        $dataByIdCreated = $dataById->getAttribute('created');
        $dataByIdUpdated = $dataById->getAttribute('updated-at');
        $dataByIdNull = $dataById->getAttribute('invalid');

        $included = $mapper->getIncluded();
        $includedOne = $included->getIncluded(0);
        $includedOneType = $includedOne->getType();
        $includedOneId = $includedOne->getId();
        $includedOneAttributes = $includedOne->getAttributes();
        $includedOneAttributesName = $includedOne->getAttribute('name');
        $includedOneAttributesAge = $includedOne->getAttribute('age');
        $includedOneAttributesGender = $includedOne->getAttribute('gender');
        $includedOneAttributesNull = $includedOne->getAttribute('invalid');

        $includedById = $included->find('people', 4);
        $includedOneType = $includedById->getType();
        $includedByIdId = $includedById->getId();
        $includedByIdAttributes = $includedById->getAttributes();
        $includedByIdAttributesName = $includedById->getAttribute('name');
        $includedByIdAttributesAge = $includedById->getAttribute('age');
        $includedByIdAttributesGender = $includedById->getAttribute('gender');
        $includedByIdAttributesNull = $includedById->getAttribute('invalid');

        $dataFindNull = $data->find(342);
        $includedFindNull = $included->find('people', 876);
        $includedFindInvalid = $included->find('invalid', 4);

        $this->validationTest(get_defined_vars());
    }

    public function testImplementationMapperMethodAlias()
    {
        $mapper = new ResponseMapper($this->dataTest());

        $meta = $mapper->meta();
        $metaTotalCount = $meta->meta('total-count');
        $metaNull = $meta->meta('invalid');

        $jsonApi = $mapper->jsonApi();
        $jsonApiVersion = $jsonApi->version();

        $links = $mapper->links();
        $linkFirst = $links->first();
        $linkPrev = $links->prev();
        $linkNext = $links->next();
        $linkLast = $links->last();
        $linkSelf = $links->self();
        $linkAbout = $links->about();
        $linkHref = $links->href();
        $linkRelated = $links->related();

        $data = $mapper->data(0);
        $dataType = $data->type();
        $dataId = $data->id();
        $dataAttributes = $data->attributes();
        $dataTitle = $data->attribute('title');
        $dataBody = $data->attribute('body');
        $dataCreated = $data->attribute('created');
        $dataUpdated = $data->attribute('updated-at');
        $dataNull = $data->attribute('invalid');

        $author = $data->relationship('author');
        $authorType = $author->type();
        $authorId = $author->id();
        $authorAttributes = $author->attributes();
        $authorName = $author->attribute('name');
        $authorAge = $author->attribute('age');
        $authorGender = $author->attribute('gender');
        $authorNull = $author->attribute('invalid');

        $dataRelationshipNull = $data->relationship('invalid');

        $dataLinks = $data->links();
        $dataLinkSelf = $dataLinks->self();
        $dataLinkRelated = $dataLinks->related();
        $dataLinkRelatedHref = $dataLinks->related('href');
        $dataLinkRelatedMeta = $dataLinks->related('meta');
        $dataLinkRelatedMetaCount = $dataLinks->related('meta.count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->type();
        $dataByIdId = $dataById->id();
        $dataByIdAttributes = $dataById->attributes();
        $dataByIdTitle = $dataById->attribute('title');
        $dataByIdBody = $dataById->attribute('body');
        $dataByIdCreated = $dataById->attribute('created');
        $dataByIdUpdated = $dataById->attribute('updated-at');
        $dataByIdNull = $dataById->attribute('invalid');

        $included = $mapper->included();
        $includedOne = $included->included(0);
        $includedOneType = $includedOne->type();
        $includedOneId = $includedOne->id();
        $includedOneAttributes = $includedOne->attributes();
        $includedOneAttributesName = $includedOne->attribute('name');
        $includedOneAttributesAge = $includedOne->attribute('age');
        $includedOneAttributesGender = $includedOne->attribute('gender');
        $includedOneAttributesNull = $includedOne->attribute('invalid');

        $includedById = $included->find('people', 4);
        $includedOneType = $includedById->type();
        $includedByIdId = $includedById->id();
        $includedByIdAttributes = $includedById->attributes();
        $includedByIdAttributesName = $includedById->attribute('name');
        $includedByIdAttributesAge = $includedById->attribute('age');
        $includedByIdAttributesGender = $includedById->attribute('gender');
        $includedByIdAttributesNull = $includedById->attribute('invalid');

        $dataFindNull = $data->find(342);
        $includedFindNull = $included->find('people', 876);
        $includedFindInvalid = $included->find('invalid', 4);

        $this->validationTest(get_defined_vars());
    }

    public function testImplementationMapperMethodWithAttributesGetAccessors()
    {
        $mapper = new ResponseMapper($this->dataTest());

        $meta = $mapper->meta();
        $metaTotalCount = $meta->meta('total-count');
        $metaNull = $meta->meta('invalid');

        $jsonApi = $mapper->jsonApi();
        $jsonApiVersion = $jsonApi->version();

        $links = $mapper->links();
        $linkFirst = $links->first();
        $linkPrev = $links->prev();
        $linkNext = $links->next();
        $linkLast = $links->last();
        $linkSelf = $links->self();
        $linkAbout = $links->about();
        $linkHref = $links->href();
        $linkRelated = $links->related();

        $data = $mapper->data(0);
        $dataType = $data->type();
        $dataId = $data->id();
        $dataAttributes = $data->attributes();
        $dataTitle = $data->getTitle();
        $dataBody = $data->getBody();
        $dataCreated = $data->getCreated();
        $dataUpdated = $data->getUpdatedAt();
        $dataNull = $data->getInvalid();

        $author = $data->author();
        $authorType = $author->type();
        $authorId = $author->id();
        $authorAttributes = $author->attributes();
        $authorName = $author->getName();
        $authorAge = $author->getAge();
        $authorGender = $author->getGender();
        $authorNull = $author->getInvalid();

        $dataRelationshipNull = $data->invalid();

        $dataLinks = $data->links();
        $dataLinkSelf = $dataLinks->self();
        $dataLinkRelated = $dataLinks->related();
        $dataLinkRelatedHref = $dataLinks->related('href');
        $dataLinkRelatedMeta = $dataLinks->related('meta');
        $dataLinkRelatedMetaCount = $dataLinks->related('meta.count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->getType();
        $dataByIdId = $dataById->getId();
        $dataByIdAttributes = $dataById->attributes();
        $dataByIdTitle = $dataById->getTitle();
        $dataByIdBody = $dataById->getBody();
        $dataByIdCreated = $dataById->getCreated();
        $dataByIdUpdated = $dataById->getUpdatedAt();
        $dataByIdNull = $dataById->getInvalid();

        $included = $mapper->included();
        $includedOne = $included->included(0);
        $includedOneType = $includedOne->type();
        $includedOneId = $includedOne->id();
        $includedOneAttributes = $includedOne->attributes();
        $includedOneAttributesName = $includedOne->getName();
        $includedOneAttributesAge = $includedOne->getAge();
        $includedOneAttributesGender = $includedOne->getGender();
        $includedOneAttributesNull = $includedOne->getInvalid();

        $includedById = $included->find('people', 4);
        $includedOneType = $includedById->type();
        $includedByIdId = $includedById->id();
        $includedByIdAttributes = $includedById->attributes();
        $includedByIdAttributesName = $includedById->getName();
        $includedByIdAttributesAge = $includedById->getAge();
        $includedByIdAttributesGender = $includedById->getGender();
        $includedByIdAttributesNull = $includedById->getInvalid();

        $dataFindNull = $data->find(342);
        $includedFindNull = $included->find('people', 876);
        $includedFindInvalid = $included->find('invalid', 4);

        $this->validationTest(get_defined_vars());
    }

    public function _testImplementationMapperMethodWithAttributesAndRelationshipAccessorsMagic()
    {
        $mapper = new ResponseMapper($this->dataTest());

        $meta = $mapper->meta();
        $metaTotalCount = $meta->meta('total-count');
        $metaNull = $meta->meta('invalid');

        $jsonApi = $mapper->jsonApi();
        $jsonApiVersion = $jsonApi->version();

        $links = $mapper->links();
        $linkFirst = $links->first();
        $linkPrev = $links->prev();
        $linkNext = $links->next();
        $linkLast = $links->last();
        $linkSelf = $links->self();
        $linkAbout = $links->about();
        $linkHref = $links->href();
        $linkRelated = $links->related();

        $data = $mapper->data(0);
        $dataType = $data->type();
        $dataId = $data->id();
        $dataAttributes = $data->attributes();
        $dataTitle = $data->title();
        $dataBody = $data->body();
        $dataCreated = $data->created();
        $dataUpdated = $data->updatedAt();
        $dataNull = $data->invalid();

        $author = $data->author();
        $authorType = $author->type();
        $authorId = $author->id();
        $authorAttributes = $author->attributes();
        $authorName = $author->name();
        $authorAge = $author->age();
        $authorGender = $author->gender();
        $authorNull = $author->invalid();

        $dataRelationshipNull = $data->invalid();

        $dataLinks = $data->links();
        $dataLinkSelf = $dataLinks->self();
        $dataLinkRelated = $dataLinks->related();
        $dataLinkRelatedHref = $dataLinks->related('href');
        $dataLinkRelatedMeta = $dataLinks->related('meta');
        $dataLinkRelatedMetaCount = $dataLinks->related('meta.count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->type();
        $dataByIdId = $dataById->id();
        $dataByIdAttributes = $dataById->attributes();
        $dataByIdTitle = $dataById->title();
        $dataByIdBody = $dataById->body();
        $dataByIdCreated = $dataById->created();
        $dataByIdUpdated = $dataById->updatedAt();
        $dataByIdNull = $dataById->invalid();

        $included = $mapper->included();
        $includedOne = $included->included(0);
        $includedOneType = $includedOne->type();
        $includedOneId = $includedOne->id();
        $includedOneAttributes = $includedOne->attributes();
        $includedOneAttributesName = $includedOne->name();
        $includedOneAttributesAge = $includedOne->age();
        $includedOneAttributesGender = $includedOne->gender();
        $includedOneAttributesNull = $includedOne->invalid();

        $includedById = $included->find('people', 4);
        $includedOneType = $includedById->type();
        $includedByIdId = $includedById->id();
        $includedByIdAttributes = $includedById->attributes();
        $includedByIdAttributesName = $includedById->name();
        $includedByIdAttributesAge = $includedById->age();
        $includedByIdAttributesGender = $includedById->gender();
        $includedByIdAttributesNull = $includedById->invalid();

        $dataFindNull = $data->find(342);
        $includedFindNull = $included->find('people', 876);
        $includedFindInvalid = $included->find('invalid', 4);

        $this->validationTest(get_defined_vars());
    }

    public function _testImplementationMapperPropertySnakeAccessors()
    {
        $mapper = new ResponseMapper($this->dataTest());

        $meta = $mapper->meta;
        $metaTotalCount = $meta->total_count;
        $metaNull = $meta->invalid;

        $jsonApi = $mapper->jsonapi;
        $jsonApiVersion = $jsonApi->version;

        $links = $mapper->links;
        $linkFirst = $links->first;
        $linkPrev = $links->prev;
        $linkNext = $links->next;
        $linkLast = $links->last;
        $linkSelf = $links->self;
        $linkAbout = $links->about;
        $linkHref = $links->href;
        $linkRelated = $links->related;

        $data = $mapper->data(0);
        $dataType = $data->type;
        $dataId = $data->id;
        $dataAttributes = $data->attributes;
        $dataTitle = $data->attribute->title;
        $dataBody = $data->attribute->body;
        $dataCreated = $data->attribute->created;
        $dataUpdated = $data->attribute->updated_at;
        $dataNull = $data->attribute->invalid;

        $author = $data->relationship->author;
        $authorType = $author->type;
        $authorId = $author->id;
        $authorAttributes = $author->attributes;
        $authorName = $author->attribute->name;
        $authorAge = $author->attribute->age;
        $authorGender = $author->attribute->gender;
        $authorNull = $author->attribute->invalid;

        $dataRelationshipNull = $data->relationship->invalid;

        $dataLinks = $data->links;
        $dataLinkSelf = $dataLinks->self;
        $dataLinkRelated = $dataLinks->related;
        $dataLinkRelatedHref = $dataLinks->related->href;
        $dataLinkRelatedMeta = $dataLinks->related->meta;
        $dataLinkRelatedMetaCount = $dataLinks->related->meta->count;

        $dataById = $data->find(25);
        $dataByIdType = $dataById->type;
        $dataByIdId = $dataById->id;
        $dataByIdAttributes = $dataById->attributes;
        $dataByIdTitle = $dataById->attribute->title;
        $dataByIdBody = $dataById->attribute->body;
        $dataByIdCreated = $dataById->attribute->created;
        $dataByIdUpdated = $dataById->attribute->updated_at;
        $dataByIdNull = $dataById->attribute->invalid;

        $included = $mapper->included;
        $includedOne = $included->included(0);
        $includedOneType = $includedOne->type;
        $includedOneId = $includedOne->id;
        $includedOneAttributes = $includedOne->attributes;
        $includedOneAttributesName = $includedOne->attribute->name;
        $includedOneAttributesAge = $includedOne->attribute->age;
        $includedOneAttributesGender = $includedOne->attribute->gender;
        $includedOneAttributesNull = $includedOne->attribute->invalid;

        $includedById = $included->find('people', 4);
        $includedOneType = $includedById->type;
        $includedByIdId = $includedById->id;
        $includedByIdAttributes = $includedById->attributes;
        $includedByIdAttributesName = $includedById->attribute->name;
        $includedByIdAttributesAge = $includedById->attribute->age;
        $includedByIdAttributesGender = $includedById->attribute->gender;
        $includedByIdAttributesNull = $includedById->attribute->invalid;

        $dataFindNull = $data->find(342);
        $includedFindNull = $included->find('people', 876);
        $includedFindInvalid = $included->find('invalid', 4);

        $this->validationTest(get_defined_vars());
    }

    public function _testImplementationMapperPropertyCamelAccessors()
    {
        $mapper = new ResponseMapper($this->dataTest());

        $meta = $mapper->meta;
        $metaTotalCount = $meta->totalCount;
        $metaNull = $meta->invalid;

        $jsonApi = $mapper->jsonapi;
        $jsonApiVersion = $jsonApi->version;

        $links = $mapper->links;
        $linkFirst = $links->first;
        $linkPrev = $links->prev;
        $linkNext = $links->next;
        $linkLast = $links->last;
        $linkSelf = $links->self;
        $linkAbout = $links->about;
        $linkHref = $links->href;
        $linkRelated = $links->related;

        $data = $mapper->data(0);
        $dataType = $data->type;
        $dataId = $data->id;
        $dataAttributes = $data->attributes;
        $dataTitle = $data->title;
        $dataBody = $data->body;
        $dataCreated = $data->created;
        $dataUpdated = $data->updatedAt;
        $dataNull = $data->invalid;

        $author = $data->author;
        $authorType = $author->type;
        $authorId = $author->id;
        $authorAttributes = $author->attributes;
        $authorName = $author->name;
        $authorAge = $author->age;
        $authorGender = $author->gender;
        $authorNull = $author->invalid;

        $dataRelationshipNull = $data->invalid;

        $dataLinks = $data->links;
        $dataLinkSelf = $dataLinks->self;
        $dataLinkRelated = $dataLinks->related;
        $dataLinkRelatedHref = $dataLinks->related->href;
        $dataLinkRelatedMeta = $dataLinks->related->meta;
        $dataLinkRelatedMetaCount = $dataLinks->related->meta->count;

        $dataById = $data->find(25);
        $dataByIdType = $dataById->type;
        $dataByIdId = $dataById->id;
        $dataByIdAttributes = $dataById->attributes;
        $dataByIdTitle = $dataById->title;
        $dataByIdBody = $dataById->body;
        $dataByIdCreated = $dataById->created;
        $dataByIdUpdated = $dataById->updatedAt;
        $dataByIdNull = $dataById->invalid;

        $included = $mapper->included;
        $includedOne = $included->included(0);
        $includedOneType = $includedOne->type;
        $includedOneId = $includedOne->id;
        $includedOneAttributes = $includedOne->attributes;
        $includedOneAttributesName = $includedOne->name;
        $includedOneAttributesAge = $includedOne->age;
        $includedOneAttributesGender = $includedOne->gender;
        $includedOneAttributesNull = $includedOne->invalid;

        $includedById = $included->find('people', 4);
        $includedOneType = $includedById->type;
        $includedByIdId = $includedById->id;
        $includedByIdAttributes = $includedById->attributes;
        $includedByIdAttributesName = $includedById->name;
        $includedByIdAttributesAge = $includedById->age;
        $includedByIdAttributesGender = $includedById->gender;
        $includedByIdAttributesNull = $includedById->invalid;

        $dataFindNull = $data->find(342);
        $includedFindNull = $included->find('people', 876);
        $includedFindInvalid = $included->find('invalid', 4);

        $this->validationTest(get_defined_vars());
    }

    private function validationTest(array $dataTest)
    {
        extract($dataTest);

        $this->assertInstanceOf(ResponseMapperInterface::class, $mapper);

        $this->assertInstanceOf(MetaMapperInterface::class, $meta);
        $this->assertEquals(2, $metaTotalCount);
        $this->assertEquals(null, $metaNull);

        $this->assertInstanceOf(JsonApiMapperInterface::class, $jsonApi);
        $this->assertEquals('1.0', $jsonApiVersion);

        $this->assertInstanceOf(LinksMapperInterface::class, $links);
        $this->assertEquals('http://example.com/articles?page[number]=1&page[size]=1', $linkFirst);
        $this->assertEquals('http://example.com/articles?page[number]=2&page[size]=1', $linkPrev);
        $this->assertEquals('http://example.com/articles?page[number]=4&page[size]=1', $linkNext);
        $this->assertEquals('http://example.com/articles?page[number]=13&page[size]=1', $linkLast);
        $this->assertEquals(null, $linkSelf);
        $this->assertEquals(null, $linkAbout);
        $this->assertEquals('http://example.com/articles?filter[body][like]=json', $linkHref);
        $this->assertEquals(null, $linkRelated);

        $this->assertInstanceOf(DataMapperInterface::class, $data);
        $this->assertEquals('articles', $dataType);
        $this->assertEquals(10, $dataId);
        $this->assertTrue(is_array($dataAttributes));
        $this->assertEquals('JSON API Rules', $dataTitle);
        $this->assertEquals('Another article.', $dataBody);
        $this->assertEquals('2015-06-22T11:58:29.000Z', $dataCreated);
        $this->assertEquals('2016-05-22T14:59:02.000Z', $dataUpdated);
        $this->assertEquals(null, $dataNull);

        $this->assertInstanceOf(DataMapperInterface::class, $author);
        $this->assertEquals('people', $authorType);
        $this->assertEquals(4, $authorId);
        $this->assertTrue(is_array($authorAttributes));
        $this->assertEquals('Sam', $authorName);
        $this->assertEquals(30, $authorAge);
        $this->assertEquals('female', $authorGender);
        $this->assertEquals(null, $authorNull);

        $this->assertEquals(null, $dataRelationshipNull);

        $this->assertInstanceOf(LinksMapperInterface::class, $dataLinks);
        $this->assertEquals('http://example.com/articles/10', $dataLinkSelf);
        $this->assertTrue(is_array($dataLinkRelated));
        $this->assertEquals('http://example.com/articles/10/comments', $dataLinkRelatedHref);
        $this->assertTrue(is_array($dataLinkRelatedMeta));
        $this->assertEquals(10, $dataLinkRelatedMetaCount);

        $this->assertInstanceOf(DataMapperInterface::class, $dataById);
        $this->assertEquals('articles', $dataByIdType);
        $this->assertEquals(25, $dataByIdId);
        $this->assertTrue(is_array($dataByIdAttributes));
        $this->assertEquals('JSON API woow', $dataByIdTitle);
        $this->assertEquals('The longest article. Ever.', $dataByIdBody);
        $this->assertEquals('2018-05-22T14:56:29.000Z', $dataByIdCreated);
        $this->assertEquals(null, $dataByIdUpdated);

        $this->assertEquals(null, $dataByIdNull);

        $this->assertInstanceOf(IncludedMapperInterface::class, $included);
        $this->assertInstanceOf(DataMapperInterface::class, $includedOne);
        $this->assertEquals('people', $includedOneType);
        $this->assertEquals(42, $includedOneId);
        $this->assertTrue(is_array($includedOneAttributes));
        $this->assertEquals('John', $includedOneAttributesName);
        $this->assertEquals(80, $includedOneAttributesAge);
        $this->assertEquals('male', $includedOneAttributesGender);
        $this->assertEquals(null, $includedOneAttributesNull);

        $this->assertInstanceOf(DataMapperInterface::class, $includedById);
        $this->assertEquals('people', $includedOneType);
        $this->assertEquals(4, $includedByIdId);
        $this->assertTrue(is_array($includedByIdAttributes));
        $this->assertEquals('Sam', $includedByIdAttributesName);
        $this->assertEquals(30, $includedByIdAttributesAge);
        $this->assertEquals('female', $includedByIdAttributesGender);
        $this->assertEquals(null, $includedByIdAttributesNull);

        $this->assertEquals(null, $dataFindNull);
        $this->assertEquals(null, $includedFindNull);
        $this->assertEquals(null, $includedFindInvalid);
    }
}
