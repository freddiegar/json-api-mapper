# placetopay/json-api-mapper

It is a mapper in PHP from response [jsonapi.org](http://jsonapi.org).

This library create a object from response json-api. Access to elements in response easily

## Install

```bash
composer require freddiegar/json-api-mapper
```

## Usage

Creating instance of Mapper

```php
use FreddieGar\JsonApiMapper\JsonApiResponse;

$jsonApiResponse = new JsonApiResponse($jsonApi);

$data = $jsonApiResponse->getData();
$included = $jsonApiResponse->getIncluded();
```

By example, get data resource

```php
echo $data->getType(); // articles
echo $data->getId(); // 1

echo $data->getAttributes(); // ['title' => 'JSON API paints my bikeshed!', 'body' => '...']
echo $data->getAttribute('created'); // 2015-05-22T14:56:29.000Z
echo $data->getAttribute('description'); // If not exist, return: null

echo $data->getRelationships(); // ['author' => ['id' => '1', 'type' => 'people']]
echo $data->getRelationship('author'); // ['id' => '1', 'type' => 'people']
echo $data->getRelationship('author')->getType(); // people
echo $data->getRelationship('author')->getId(); // 1
```

By example, get included

```php
echo $included->get(0); // return DataMapperInterface
echo $included->get(0)->getType(); // people
echo $included->get(0)->getId(); // 42
echo $included->get(1); // null, it is not defined in response
```

By example, get errors

```php
$allErrors = $jsonApiResponse->getErrors();
$firstError = $jsonApiResponse->getErrors(0);
$secondError = $jsonApiResponse->getErrors(1); // null, it is not defined in response

echo $firstError->getStatus(); // 422
echo $firstError->getSource(); // ['pointer' => '/data/attributes/first-name']
echo $firstError->getTitle(); // Invalid Attribute
echo $firstError->getDetail(); // First name must contain at least three characters.

```

## Alias in JsonApiResponse class

| Method         | Alias           | Property        |Description                                                     |
|----------------|-----------------|-----------------|----------------------------------------------------------------|
| getData()      | data()          | data            | Return object [DataMapper][link-data-mapper] if exists in response, else null      |
| getErrors()    | errors()        | errors          | Return object [ErrorsMapper][link-errors-mapper] if exists in response, else null   |

[link-data-mapper]: https://github.com/freddiegar/json-api-mapper/blob/master/src/Contracts/DataMapperInterface.php
[link-errors-mapper]: https://github.com/freddiegar/json-api-mapper/blob/master/src/Contracts/ErrorsMapperInterface.php
[link-meta-mapper]: https://github.com/freddiegar/json-api-mapper/blob/master/src/Contracts/MetaMapperInterface.php

## Response [json-api](http://jsonapi.org/examples/#sparse-fieldsets) Resource used in this example

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

## Response [json-api](http://jsonapi.org/examples/#sparse-fieldsets) Errors used in this example

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
