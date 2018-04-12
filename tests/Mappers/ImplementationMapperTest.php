<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\ErrorsMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\IncludedMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\JsonApiMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\MetaMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\RelatedMapperInterface;
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
        "first":"http://example.com/articles?page[number]=1&page[size]=1",
        "prev":"http://example.com/articles?page[number]=2&page[size]=1",
        "next":"http://example.com/articles?page[number]=4&page[size]=1",
        "last":"http://example.com/articles?page[number]=13&page[size]=1",
        "related":"http://example.com/comments"
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
                "title":"JSON API paints my head!",
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
                "related":null
            }
        },
        {
            "type":"articles",
            "id":"25",
            "attributes":{
                "title":"JSON API yeah",
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

    private function dataErrors()
    {
        return <<<JSON
{
    "errors": [
        {
            "id": "A33",
            "links": {
                "about": "http://service.org/help/me"
            },
            "status": "422",
            "code": "001-A",
            "title":  "Invalid Attribute",
            "detail": "Username is required.",
            "source": { 
                "pointer": "/data/attributes/username" 
            },
            "meta": {
                "request-at": "2018-01-24T10:44:44.000Z",
                "testing": "yes"
            }
        },
        {
            "id": "53453",
            "links": {
                "about": null
            },
            "status": "419",
            "code": "355",
            "title":  "Too many request"
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
        $dataLinkRelatedHref = $dataLinks->getRelated()->getHref();
        $dataLinkRelatedMeta = $dataLinks->getRelated()->getMeta();
        $dataLinkRelatedMetaCount = $dataLinks->getRelated()->getMeta('count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->getType();
        $dataByIdId = $dataById->getId();
        $dataByIdAttributes = $dataById->getAttributes();
        $dataByIdTitle = $dataById->getAttribute('title');
        $dataByIdBody = $dataById->getAttribute('body');
        $dataByIdCreated = $dataById->getAttribute('created');
        $dataByIdUpdated = $dataById->getAttribute('updated-at');
        $dataByIdNull = $dataById->getAttribute('invalid');

        $dataLinksTwo = $data->find(20)->getLinks();
        $dataLinkSelfTwo = $dataLinksTwo->getSelf();
        $dataLinkRelatedTwo = $dataLinksTwo->getRelated();

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
        $dataLinkRelatedHref = $dataLinks->related()->href();
        $dataLinkRelatedMeta = $dataLinks->related()->meta();
        $dataLinkRelatedMetaCount = $dataLinks->related()->meta('count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->type();
        $dataByIdId = $dataById->id();
        $dataByIdAttributes = $dataById->attributes();
        $dataByIdTitle = $dataById->attribute('title');
        $dataByIdBody = $dataById->attribute('body');
        $dataByIdCreated = $dataById->attribute('created');
        $dataByIdUpdated = $dataById->attribute('updated-at');
        $dataByIdNull = $dataById->attribute('invalid');

        $dataLinksTwo = $data->find(20)->links();
        $dataLinkSelfTwo = $dataLinksTwo->self();
        $dataLinkRelatedTwo = $dataLinksTwo->related();

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
        $dataLinkRelatedHref = $dataLinks->related()->href();
        $dataLinkRelatedMeta = $dataLinks->related()->meta();
        $dataLinkRelatedMetaCount = $dataLinks->related()->meta('count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->getType();
        $dataByIdId = $dataById->getId();
        $dataByIdAttributes = $dataById->attributes();
        $dataByIdTitle = $dataById->getTitle();
        $dataByIdBody = $dataById->getBody();
        $dataByIdCreated = $dataById->getCreated();
        $dataByIdUpdated = $dataById->getUpdatedAt();
        $dataByIdNull = $dataById->getInvalid();

        $dataLinksTwo = $data->find(20)->links();
        $dataLinkSelfTwo = $dataLinksTwo->self();
        $dataLinkRelatedTwo = $dataLinksTwo->related();


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

    public function testImplementationMapperMethodWithoutAttributesAndRelationshipAccessorsMagic()
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
        $dataLinkRelatedHref = $dataLinks->related()->href();
        $dataLinkRelatedMeta = $dataLinks->related()->meta();
        $dataLinkRelatedMetaCount = $dataLinks->related()->meta('count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->type();
        $dataByIdId = $dataById->id();
        $dataByIdAttributes = $dataById->attributes();
        $dataByIdTitle = $dataById->title();
        $dataByIdBody = $dataById->body();
        $dataByIdCreated = $dataById->created();
        $dataByIdUpdated = $dataById->updatedAt();
        $dataByIdNull = $dataById->invalid();

        $dataLinksTwo = $data->find(20)->links();
        $dataLinkSelfTwo = $dataLinksTwo->self();
        $dataLinkRelatedTwo = $dataLinksTwo->related();

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

    public function testImplementationMapperPropertySnakeAccessors()
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
        $dataLinkRelatedMetaCount = $dataLinks->related->meta('count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->type;
        $dataByIdId = $dataById->id;
        $dataByIdAttributes = $dataById->attributes;
        $dataByIdTitle = $dataById->attribute->title;
        $dataByIdBody = $dataById->attribute->body;
        $dataByIdCreated = $dataById->attribute->created;
        $dataByIdUpdated = $dataById->attribute->updated_at;
        $dataByIdNull = $dataById->attribute->invalid;

        $dataLinksTwo = $data->find(20)->links;
        $dataLinkSelfTwo = $dataLinksTwo->self;
        $dataLinkRelatedTwo = $dataLinksTwo->related;

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

    public function testImplementationMapperPropertyCamelAccessors()
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
        $linkRelated = $links->related;

        $data = $mapper->data(0);
        $dataType = $data->type;
        $dataId = $data->id;
        $dataAttributes = $data->attributes;
        $dataTitle = $data->attribute->title;
        $dataBody = $data->attribute->body;
        $dataCreated = $data->attribute->created;
        $dataUpdated = $data->attribute->updatedAt;
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
        $dataLinkRelatedMetaCount = $dataLinks->related->meta('count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->type;
        $dataByIdId = $dataById->id;
        $dataByIdAttributes = $dataById->attributes;
        $dataByIdTitle = $dataById->attribute->title;
        $dataByIdBody = $dataById->attribute->body;
        $dataByIdCreated = $dataById->attribute->created;
        $dataByIdUpdated = $dataById->attribute->updatedAt;
        $dataByIdNull = $dataById->attribute->invalid;

        $dataLinksTwo = $data->find(20)->links;
        $dataLinkSelfTwo = $dataLinksTwo->self;
        $dataLinkRelatedTwo = $dataLinksTwo->related;

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

    public function testImplementationMapperPropertyWithoutAttributesAndRelationshipsSnakeAccessors()
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
        $linkRelated = $links->related;

        $data = $mapper->data(0);
        $dataType = $data->type;
        $dataId = $data->id;
        $dataAttributes = $data->attributes;
        $dataTitle = $data->title;
        $dataBody = $data->body;
        $dataCreated = $data->created;
        $dataUpdated = $data->updated_at;
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
        $dataLinkRelatedMetaCount = $dataLinks->related->meta('count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->type;
        $dataByIdId = $dataById->id;
        $dataByIdAttributes = $dataById->attributes;
        $dataByIdTitle = $dataById->title;
        $dataByIdBody = $dataById->body;
        $dataByIdCreated = $dataById->created;
        $dataByIdUpdated = $dataById->updated_at;
        $dataByIdNull = $dataById->invalid;

        $dataLinksTwo = $data->find(20)->links;
        $dataLinkSelfTwo = $dataLinksTwo->self;
        $dataLinkRelatedTwo = $dataLinksTwo->related;

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

    public function testImplementationMapperPropertyWithoutAttributesAndRelationshipsCamelAccessors()
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
        $dataLinkRelatedMetaCount = $dataLinks->related->meta('count');

        $dataById = $data->find(25);
        $dataByIdType = $dataById->type;
        $dataByIdId = $dataById->id;
        $dataByIdAttributes = $dataById->attributes;
        $dataByIdTitle = $dataById->title;
        $dataByIdBody = $dataById->body;
        $dataByIdCreated = $dataById->created;
        $dataByIdUpdated = $dataById->updatedAt;
        $dataByIdNull = $dataById->invalid;

        $dataLinksTwo = $data->find(20)->links;
        $dataLinkSelfTwo = $dataLinksTwo->self;
        $dataLinkRelatedTwo = $dataLinksTwo->related;

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

    public function testImplementationErrorsMapperSimpleGet()
    {
        $mapper = new ResponseMapper($this->dataErrors());
        $errors = $mapper->getErrors();
        $errorAll = $errors->all();

        $errorOne = $mapper->getErrors(0);
        $errorId = $errorOne->getId();
        $errorAbout = $errorOne->getAbout();
        $errorStatus = $errorOne->getStatus();
        $errorCode = $errorOne->getCode();
        $errorTitle = $errorOne->getTitle();
        $errorDetail = $errorOne->getDetail();
        $errorSource = $errorOne->getSource();
        $errorMeta = $errorOne->getMeta();
        $errorMetaRequestAt = $errorOne->getMeta('request-at');
        $errorMetaTesting = $errorOne->getMeta('testing');
        $errorMetaNull = $errorOne->getMeta('invalid');

        $errorTwo = $mapper->getErrors(1);
        $errorTwoId = $errorTwo->getId();
        $errorTwoAbout = $errorTwo->getAbout();
        $errorTwoStatus = $errorTwo->getStatus();
        $errorTwoCode = $errorTwo->getCode();
        $errorTwoTitle = $errorTwo->getTitle();
        $errorTwoDetail = $errorTwo->getDetail();
        $errorTwoSource = $errorTwo->getSource();
        $errorTwoMeta = $errorTwo->getMeta();

        $errorNull = $mapper->getErrors(2);

        $this->validationErrors(get_defined_vars());
    }

    public function testImplementationErrorsMapperSimpleAlias()
    {
        $mapper = new ResponseMapper($this->dataErrors());
        $errors = $mapper->errors();
        $errorAll = $errors->all();

        $errorOne = $mapper->errors(0);
        $errorId = $errorOne->id();
        $errorAbout = $errorOne->about();
        $errorStatus = $errorOne->status();
        $errorCode = $errorOne->code();
        $errorTitle = $errorOne->title();
        $errorDetail = $errorOne->detail();
        $errorSource = $errorOne->source();
        $errorMeta = $errorOne->meta();
        $errorMetaRequestAt = $errorOne->meta('request-at');
        $errorMetaTesting = $errorOne->meta('testing');
        $errorMetaNull = $errorOne->meta('invalid');

        $errorTwo = $mapper->errors(1);
        $errorTwoId = $errorTwo->id();
        $errorTwoAbout = $errorTwo->about();
        $errorTwoStatus = $errorTwo->status();
        $errorTwoCode = $errorTwo->code();
        $errorTwoTitle = $errorTwo->title();
        $errorTwoDetail = $errorTwo->detail();
        $errorTwoSource = $errorTwo->source();
        $errorTwoMeta = $errorTwo->meta();

        $errorNull = $mapper->errors(2);

        $this->validationErrors(get_defined_vars());
    }

    public function testImplementationErrorsMapperPropertySnakeAccessors()
    {
        $mapper = new ResponseMapper($this->dataErrors());

        $errors = $mapper->errors;
        $errorAll = $errors->all();

        $errorOne = $mapper->errors(0);
        $errorId = $errorOne->id;
        $errorAbout = $errorOne->about;
        $errorStatus = $errorOne->status;
        $errorCode = $errorOne->code;
        $errorTitle = $errorOne->title;
        $errorDetail = $errorOne->detail;
        $errorSource = $errorOne->source;
        $errorMeta = $errorOne->meta;
        $errorMetaRequestAt = $errorOne->meta('request-at');
        $errorMetaTesting = $errorOne->meta('testing');
        $errorMetaNull = $errorOne->meta('invalid');

        $errorTwo = $mapper->errors(1);
        $errorTwoId = $errorTwo->id;
        $errorTwoAbout = $errorTwo->about;
        $errorTwoStatus = $errorTwo->status;
        $errorTwoCode = $errorTwo->code;
        $errorTwoTitle = $errorTwo->title;
        $errorTwoDetail = $errorTwo->detail;
        $errorTwoSource = $errorTwo->source;
        $errorTwoMeta = $errorTwo->meta();

        $errorNull = $mapper->errors(2);

        $this->validationErrors(get_defined_vars());
    }

    public function testImplementationErrorsMapperPropertyCamelAccessors()
    {
        $mapper = new ResponseMapper($this->dataErrors());
        $errors = $mapper->errors;
        $errorAll = $errors->all();

        $errorOne = $mapper->errors(0);
        $errorId = $errorOne->id;
        $errorAbout = $errorOne->about;
        $errorStatus = $errorOne->status;
        $errorCode = $errorOne->code;
        $errorTitle = $errorOne->title;
        $errorDetail = $errorOne->detail;
        $errorSource = $errorOne->source;
        $errorMeta = $errorOne->meta();
        $errorMetaRequestAt = $errorOne->meta('request-at');
        $errorMetaTesting = $errorOne->meta('testing');
        $errorMetaNull = $errorOne->meta('invalid');

        $errorTwo = $mapper->errors(1);
        $errorTwoId = $errorTwo->id;
        $errorTwoAbout = $errorTwo->about;
        $errorTwoStatus = $errorTwo->status;
        $errorTwoCode = $errorTwo->code;
        $errorTwoTitle = $errorTwo->title;
        $errorTwoDetail = $errorTwo->detail;
        $errorTwoSource = $errorTwo->source;
        $errorTwoMeta = $errorTwo->meta();

        $errorNull = $mapper->errors(2);

        $this->validationErrors(get_defined_vars());
    }

    private function validationTest(array $dataTest)
    {
        /**
         * @var $mapper
         * @var $meta
         * @var $metaTotalCount
         * @var $metaNull
         * @var $jsonApi
         * @var $jsonApiVersion
         * @var $links
         * @var $linkFirst
         * @var $linkPrev
         * @var $linkNext
         * @var $linkLast
         * @var $linkSelf
         * @var $linkAbout
         * @var $linkRelated
         * @var $data
         * @var $dataType
         * @var $dataId
         * @var $dataAttributes
         * @var $dataTitle
         * @var $dataBody
         * @var $dataCreated
         * @var $dataUpdated
         * @var $dataNull
         * @var $author
         * @var $authorType
         * @var $authorId
         * @var $authorAttributes
         * @var $authorName
         * @var $authorAge
         * @var $authorGender
         * @var $authorNull
         * @var $dataRelationshipNull
         * @var $dataLinks
         * @var $dataLinkSelf
         * @var $dataLinkRelated
         * @var $dataLinkRelatedHref
         * @var $dataLinkRelatedMeta
         * @var $dataLinkRelatedMetaCount
         * @var $dataById
         * @var $dataByIdType
         * @var $dataByIdId
         * @var $dataByIdAttributes
         * @var $dataByIdTitle
         * @var $dataByIdBody
         * @var $dataByIdCreated
         * @var $dataByIdUpdated
         * @var $dataByIdNull
         * @var $dataLinksTwo
         * @var $dataLinkSelfTwo
         * @var $dataLinkRelatedTwo
         * @var $included
         * @var $includedOne
         * @var $includedOneType
         * @var $includedOneId
         * @var $includedOneAttributes
         * @var $includedOneAttributesName
         * @var $includedOneAttributesAge
         * @var $includedOneAttributesGender
         * @var $includedOneAttributesNull
         * @var $includedById
         * @var $includedOneType
         * @var $includedByIdId
         * @var $includedByIdAttributes
         * @var $includedByIdAttributesName
         * @var $includedByIdAttributesAge
         * @var $includedByIdAttributesGender
         * @var $includedByIdAttributesNull
         * @var $dataFindNull
         * @var $includedFindNull
         * @var $includedFindInvalid
         */
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
        $this->assertEquals('http://example.com/comments', $linkRelated);

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
        $this->assertInstanceOf(RelatedMapperInterface::class, $dataLinkRelated);
        $this->assertEquals('http://example.com/articles/10/comments', $dataLinkRelatedHref);
        $this->assertTrue(is_array($dataLinkRelatedMeta));
        $this->assertEquals(10, $dataLinkRelatedMetaCount);

        $this->assertInstanceOf(DataMapperInterface::class, $dataById);
        $this->assertEquals('articles', $dataByIdType);
        $this->assertEquals(25, $dataByIdId);
        $this->assertTrue(is_array($dataByIdAttributes));
        $this->assertEquals('JSON API yeah', $dataByIdTitle);
        $this->assertEquals('The longest article. Ever.', $dataByIdBody);
        $this->assertEquals('2018-05-22T14:56:29.000Z', $dataByIdCreated);
        $this->assertEquals(null, $dataByIdUpdated);

        $this->assertEquals(null, $dataByIdNull);

        $this->assertInstanceOf(LinksMapperInterface::class, $dataLinksTwo);
        $this->assertEquals('http://example.com/articles/20', $dataLinkSelfTwo);
        $this->assertEquals(null, $dataLinkRelatedTwo);

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

    private function validationErrors(array $dataErrors)
    {
        /**
         * @var $errors
         * @var $errorAll
         * @var $errorOne
         * @var $errorId
         * @var $errorAbout
         * @var $errorStatus
         * @var $errorCode
         * @var $errorTitle
         * @var $errorDetail
         * @var $errorSource
         * @var $errorMeta
         * @var $errorMetaRequestAt
         * @var $errorMetaTesting
         * @var $errorMetaNull
         * @var $errorTwo
         * @var $errorTwoId
         * @var $errorTwoAbout
         * @var $errorTwoStatus
         * @var $errorTwoCode
         * @var $errorTwoTitle
         * @var $errorTwoDetail
         * @var $errorTwoSource
         * @var $errorTwoMeta
         * @var $errorTwoMetaRequestAt
         * @var $errorTwoMetaTesting
         * @var $errorTwoMetaNull
         * @var $errorNull
         */
        extract($dataErrors);

        $this->assertInstanceOf(ErrorsMapperInterface::class, $errors);
        $this->assertTrue(is_array($errorAll));

        $this->assertInstanceOf(ErrorsMapperInterface::class, $errorOne);
        $this->assertEquals("A33", $errorId);
        $this->assertEquals('http://service.org/help/me', $errorAbout);
        $this->assertEquals(422, $errorStatus);
        $this->assertEquals('001-A', $errorCode);
        $this->assertEquals('Invalid Attribute', $errorTitle);
        $this->assertEquals('Username is required.', $errorDetail);
        $this->assertTrue(is_array($errorSource));
        $this->assertTrue(is_array($errorMeta));
        $this->assertEquals('2018-01-24T10:44:44.000Z', $errorMetaRequestAt);
        $this->assertEquals('yes', $errorMetaTesting);
        $this->assertEquals(null, $errorMetaNull);

        $this->assertInstanceOf(ErrorsMapperInterface::class, $errorTwo);
        $this->assertEquals(53453, $errorTwoId);
        $this->assertEquals(null, $errorTwoAbout);
        $this->assertEquals(419, $errorTwoStatus);
        $this->assertEquals('355', $errorTwoCode);
        $this->assertEquals('Too many request', $errorTwoTitle);
        $this->assertEquals(null, $errorTwoDetail);
        $this->assertEquals(null, $errorTwoSource);
        $this->assertEquals(null, $errorTwoMeta);

        $this->assertEquals(null, $errorNull);
    }
}
