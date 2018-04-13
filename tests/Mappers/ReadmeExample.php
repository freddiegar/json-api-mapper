<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use FreddieGar\JsonApiMapper\JsonApiMapper;

class ReadmeExample
{
    public function testReadmeExample()
    {
        $jsonApiResponse = '
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
';

        $jsonApi = new JsonApiMapper($jsonApiResponse);

        $data = $jsonApi->getData(0);
        $included = $jsonApi->getIncluded();

        echo $data->getType() . PHP_EOL; // articles
        echo $data->getId() . PHP_EOL; // 1

        echo print_r($data->getAttributes(), true) . PHP_EOL; // ['title' => 'JSON API paints my bikeshed!', 'body' => '...']
        echo $data->getAttribute('created') . PHP_EOL; // 2015-05-22T14:56:29.000Z
        echo $data->getAttribute('description') . PHP_EOL; // If not exist, return: null

        echo print_r($data->getRelationships(), true) . PHP_EOL; // ['author' => ['id' => '1', 'type' => 'people']]
        echo get_class($data->getRelationship('author')) . PHP_EOL; // return DataMapperInterface
        echo $data->getRelationship('author')->getType() . PHP_EOL; // people
        echo $data->getRelationship('author')->getId() . PHP_EOL; // 1

        echo get_class($included->getIncluded(0)) . PHP_EOL; // return DataMapperInterface
        echo $included->getIncluded(0)->getType() . PHP_EOL; // people
        echo $included->getIncluded(0)->getId() . PHP_EOL; // 42
        echo $included->getIncluded(0)->getName() . PHP_EOL; // John
        echo $included->getIncluded(1) . PHP_EOL; // null, it is not defined in response

        // Finding
        // $dataWithIdTwo = $data->find(2); // Return DataMapperInterface if exist else null
        // $dataWithIdThree = $included->find('people', 3); // Return DataMapperInterface if exist else null

    }

    public function testReadmeExampleError()
    {
        $jsonApiResponse = '
{
  "errors": [
      {
        "status": "422",
        "source": { "pointer": "/data/attributes/first-name" },
        "title":  "Invalid Attribute",
        "detail": "First name must contain at least three characters."
      }
    ]
}';

        $jsonApi = new JsonApiMapper($jsonApiResponse);

        echo get_class($jsonApi->getErrors()) . PHP_EOL; // Return ErrorsMapperInterface

        $firstError = $jsonApi->getErrors(0); // Get first error

        echo $firstError->getStatus() . PHP_EOL; // 422
        echo print_r($firstError->getSource(), true) . PHP_EOL; // ['pointer' => '/data/attributes/first-name']
        echo $firstError->getTitle() . PHP_EOL; // Invalid Attribute
        echo $firstError->getDetail() . PHP_EOL; // First name must contain at least three characters.

        // $secondError = $jsonApi->getErrors(1); // null, it is not defined in response
    }
}
