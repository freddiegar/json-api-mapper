<?php

namespace PlacetoPay\JsonApiMapper\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * @return string
     */
    protected function instanceDataSimple()
    {
        return <<<JSON
{
    "data": {
        "type":"users",
        "id":"1449216560",
        "attributes":{
            "name":"Jon Doe",
            "language-id":"es",
            "description":null,
            "created-at":"2018-02-14T16:03:43.000Z",
            "updated-at":"2018-02-14T17:05:35.000Z"
        },
        "relationships":{
            "language":{
                "data":{
                    "type":"languages",
                    "id":"es"
                }
            }
        },
        "links":{
            "self":"http://example.com/posts/1449216560",
            "related": {
                "href": "http://example.com/posts/1449216560/comments",
                "meta": {
                   "count": 10
                }
            }
        }
    }
}
JSON;
    }

    /**
     * @return string
     */
    protected function instanceDataMultiple()
    {
        return <<<JSON
{
    "data":[
        {
            "type":"users",
            "id":"1",
            "attributes":{
                "name":"Jon Doe",
                "language-id":"es",
                "description":null,
                "created-at":"2018-02-14T16:03:43.000Z",
                "updated-at":"2018-02-14T17:05:35.000Z"
            }
        },
        {
            "type":"users",
            "id":"2",
            "attributes":{
                "name":"Sam Doe",
                "language-id":"es",
                "description":"Un-know",
                "created-at":"2018-03-14T06:13:55.000Z",
                "updated-at":null
            }
        },
        {
            "type":"users",
            "id":"3",
            "attributes":{
                "name":"Steve Jobs",
                "language-id":"es",
                "description":"Engineer",
                "created-at":"2017-12-24T10:53:43.000Z",
                "updated-at":null
            }
        },
        {
            "type":"users",
            "id":"4",
            "attributes":{
                "name":"",
                "language-id":"es",
                "description":"Literature",
                "created-at":"2008-02-11T01:01:41.000Z",
                "updated-at":null
            }
        }
    ]
}
JSON;
    }

    protected function instanceErrorsSimple()
    {
        return <<<JSON
{
    "errors": [
        {
            "id": "3452435234",
            "links": {
                "about": "http://example.com/help/me"
            },
            "status": "422",
            "code": "001",
            "title":  "Invalid Attribute",
            "detail": "First name must contain at least three characters.",
            "source": { 
                "pointer": "/data/attributes/first-name" 
            },
            "meta": {
                "copyright": "Copyright 2015 Example Corp."
            }
        }
    ]
}
JSON;
    }

    protected function instanceErrorsMultiple()
    {
        return <<<JSON
{
    "errors": [
        {
            "status": "403",
            "source": { 
                "pointer": "/data/attributes/secret-powers" 
            },
            "detail": "Editing secret powers is not authorized on Sundays."
        },
        {
            "code": "002",
            "status": "422",
            "source": { 
                "pointer": "/data/attributes/volume" 
            },
            "detail": "Volume does not, in fact, go to 11."
        },
        {
            "status": "500",
            "source": { 
                "pointer": "/data/attributes/reputation" 
            },
            "title": "The backend responded with an error",
            "detail": "Reputation service not responding after three requests."
        }
    ]
}
JSON;
    }

    /**
     * @return string
     */
    protected function instanceMeta()
    {
        return <<<JSON
{
    "meta": {
        "copyright": "Copyright 2015 Example Corp.",
        "authors": [
            "Yehuda Katz",
            "Steve Klabnik",
            "Dan Gebhardt",
            "Tyler Kellen"
        ]
    }
}
JSON;
    }

    /**
     * @return string
     */
    protected function instanceJsonApi()
    {
        return <<<JSON
{
    "jsonapi": {
        "version": "1.0"
    }
}
JSON;
    }

    /**
     * @return string
     */
    protected function instanceLinks()
    {
        return <<<JSON
{
    "links": {
        "self": "http://example.com/posts",
        "first": "http://example.com/articles?page[number]=1&page[size]=1",
        "prev": "http://example.com/articles?page[number]=2&page[size]=1",
        "next": "http://example.com/articles?page[number]=4&page[size]=1",
        "last": "http://example.com/articles?page[number]=13&page[size]=1",
        "related": {
            "href": "http://example.com/articles/1/comments",
            "meta": {
                "count": 10
            }
        }
    }
}
JSON;
    }

    /**
     * @return string
     */
    protected function instanceDataWithIncluded()
    {
        return <<<JSON
{
    "data":[
        {}
    ],
    "included":[
        {
            "type":"people",
            "id":"9",
            "attributes":{
                "first-name":"Dan",
                "last-name":"Gebhardt",
                "twitter":"dgeb"
            },
            "links":{
                "self":"http://example.com/people/9"
            }
        },
        {
            "type":"comments",
            "id":"5",
            "attributes":{
                "body":"First!"
            },
            "relationships":{
                "author":{
                    "data":{
                        "type":"people",
                        "id":"2"
                    }
                }
            },
            "links":{
                "self":"http://example.com/comments/5"
            }
        },
        {
            "type":"comments",
            "id":"12",
            "attributes":{
                "body":"I like XML better"
            },
            "relationships":{
                "author":{
                    "data":{
                        "type":"people",
                        "id":"9"
                    }
                }
            },
            "links":{
                "self":"http://example.com/comments/12"
            }
        }
    ]
}
JSON;
    }
}
