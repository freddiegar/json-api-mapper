# JSON Api Mapper

It is a mapper in PHP from response [jsonapi.org](http://jsonapi.org).

This library create a object from response json-api. Access to elements in response easily

## Status Branch

<a href="https://travis-ci.org/freddiegar/json-api-mapper"><img src="https://travis-ci.org/freddiegar/json-api-mapper.svg?branch=master" alt="Master Branch"></a>
<a href="https://travis-ci.org/freddiegar/json-api-mapper"><img src="https://travis-ci.org/freddiegar/json-api-mapper.svg?branch=develop" alt="Develop Branch"></a>

## Requisites

- php >= 7.1.3

## Install

```bash
composer require freddiegar/json-api-mapper
```

## Usage

Creating instance of Mapper, see $jsonApiResponse [here][link-response-data]

```php
use FreddieGar\JsonApiMapper\JsonApiMapper;

$jsonApi = new JsonApiMapper($jsonApiResponse);

$data = $jsonApi->getData(0);
$included = $jsonApi->getIncluded();
```

By example, get data resource

```php
echo $data->getType(); // articles
echo $data->getId(); // 1

echo print_r($data->getAttributes(), true); // ['title' => 'JSON API paints my bikeshed!', 'body' => '...']
echo $data->getAttribute('created'); // 2015-05-22T14:56:29.000Z
echo $data->getAttribute('description'); // If not exist, return: null

echo print_r($data->getRelationships(), true); // ['author' => ['id' => '1', 'type' => 'people']]
echo get_class($data->getRelationship('author')); // return DataMapperInterface
echo $data->getRelationship('author')->getType(); // people
echo $data->getRelationship('author')->getId(); // 1
```

By example, get included

```php
echo get_class($included->getIncluded(0)); // return DataMapperInterface
echo $included->getIncluded(0)->getType(); // people
echo $included->getIncluded(0)->getId(); // 42
echo $included->getIncluded(0)->getName(); // John
echo $included->getIncluded(1); // null, it is not defined in response
```

By example, get errors, see $jsonApiResponse [here][link-response-errors]

```php
$jsonApi = new JsonApiMapper($jsonApiResponse);

echo get_class($jsonApi->getErrors()); // Return ErrorsMapperInterface

$firstError = $jsonApi->getErrors(0); // Get first error

echo $firstError->getStatus(); // 422
echo print_r($firstError->getSource(), true); // ['pointer' => '/data/attributes/first-name']
echo $firstError->getTitle(); // Invalid Attribute
echo $firstError->getDetail(); // First name must contain at least three characters.

$secondError = $jsonApi->getErrors(1); // null, it is not defined in response
```

## Find

### Get data with `id` = 2

```php
$dataWithIdTwo = $data->find(2); // Return DataMapperInterface if exist else null
```

### Get included by `type` = people

```php
$dataPeople = $included->find('people'); // Return DataMapperInterface if exist else null
```

### Get included with `type` = people and `id` = 3

```php
$dataPeopleWithIdThree = $included->find('people', 3); // Return DataMapperInterface if exist else null
// OR
$peopleWithIdThree = $dataPeople->find(3); // Return DataMapperInterface if exist else null
```

## Alias in JsonApiResponse class

You can use any option to access to data in that response

| Method[*][link-performance]         | Alias           | Property        |Description                                                       |
|----------------|-----------------|-----------------|---------------------------------------------------------------------------------------|
| getData()      | data()          | data            | Return object [DataMapper][link-data-mapper] if exists in response, else null         |
| getErrors()    | errors()        | errors          | Return object [ErrorsMapper][link-errors-mapper] if exists in response, else null     |
| getMeta()      | meta()          | meta            | Return object [MetaMapper][link-meta-mapper] if exists in response, else null         |
| getJsonApi()   | jsonapi()       | jsonapi         | Return object [JsonApiMapper][link-jsonapi-mapper] if exists in response, else null   |
| getIncluded()  | included()      | included        | Return object [IncludedMapper][link-included-mapper] if exists in response, else null |
| getLinks()     | links()         | links           | Return object [LinksMapper][link-links-mapper] if exists in response, else null       |

## Performance
<a name="performance"></a>

You will prefer to use get* (getData(), getErrors()) methods accessors, they are direct call, any other ways are overloading (`__call`  and `__get`), this [are](https://gist.github.com/bwaidelich/7334680) __slower__

## Response Used In Example

You can see all example [here][link-example]

### Response [json-api](http://jsonapi.org/examples/#sparse-fieldsets) Resource used in this example
<a name="response-data"></a>

```json
{
  "data": [{
    "type": "articles",
    "id": "1",
    "attributes": {
      "title": "JSON API paints my bikeshed!",
      "body": "The shortest article. Ever.",
      "created": "2015-05-22T14:56:29.000Z",
      "updated": "2015-05-22T14:56:28.000Z"
    },
    "relationships": {
      "author": {
        "data": {"id": "42", "type": "people"}
      }
    }
  }],
  "included": [
    {
      "type": "people",
      "id": "42",
      "attributes": {
        "name": "John",
        "age": 80,
        "gender": "male"
      }
    }
  ]
}
```

### Response [json-api](http://jsonapi.org/examples/#error-objects-basics) Errors used in this example
<a name="response-errors"></a>

```json
{
  "errors": [
      {
        "status": "422",
        "source": { "pointer": "/data/attributes/first-name" },
        "title":  "Invalid Attribute",
        "detail": "First name must contain at least three characters."
      }
    ]
}
```

## License

[MIT][link-license]

[link-data-mapper]: https://github.com/freddiegar/json-api-mapper/blob/master/src/Contracts/DataMapperInterface.php
[link-errors-mapper]: https://github.com/freddiegar/json-api-mapper/blob/master/src/Contracts/ErrorsMapperInterface.php
[link-meta-mapper]: https://github.com/freddiegar/json-api-mapper/blob/master/src/Contracts/MetaMapperInterface.php
[link-jsonapi-mapper]: https://github.com/freddiegar/json-api-mapper/blob/master/src/Contracts/ObjectJsonApiMapperInterface.php
[link-included-mapper]: https://github.com/freddiegar/json-api-mapper/blob/master/src/Contracts/IncludedMapperInterface.php
[link-links-mapper]: https://github.com/freddiegar/json-api-mapper/blob/master/src/Contracts/LinksMapperInterface.php
[link-performance]: #performance
[link-response-data]: #response-data
[link-response-errors]: #response-errors
[link-example]: https://github.com/freddiegar/json-api-mapper/blob/master/test/Mappers/ReadmeExample.php
[link-license]: https://en.wikipedia.org/wiki/MIT_License
