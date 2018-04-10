# placetopay/json-api-mapper

It is a mapper in PHP from response [jsonapi.org](http://jsonapi.org).

## Usage

Creating instance of Mapper

```php
use PlacetoPay\JsonApiMapper\Mapper;

$mapper = new Mapper($jsonApi);

$data = $mapper->getData();
$included = $mapper->getIncluded();
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
echo $included->get(1); // null
```

By example, get errors

```php
$allErrors = $mapper->getErrors();
$firstError = $mapper->getErrors(0);
$secondError = $mapper->getErrors(1); // null

echo $firstError->getStatus(); // 422
echo $firstError->getSource(); // ['pointer' => '/data/attributes/first-name']
echo $firstError->getTitle(); // Invalid Attribute
echo $firstError->getDetail(); // First name must contain at least three characters.

// Another options
echo $firstError->getId(); // null
echo $firstError->getCode(); // null
echo $firstError->getAbout(); // null
```

## Response [json-api](http://jsonapi.org/examples/#sparse-fieldsets) data used in this example

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

## Response [json-api](http://jsonapi.org/examples/#sparse-fieldsets) errors used in this example

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
