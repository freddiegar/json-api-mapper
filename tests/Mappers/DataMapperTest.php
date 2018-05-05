<?php

namespace FreddieGar\JsonApiMapper\Tests\Mappers;

use Exception;
use FreddieGar\JsonApiMapper\Contracts\DataMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\LinksMapperInterface;
use FreddieGar\JsonApiMapper\Contracts\RelatedMapperInterface;
use FreddieGar\JsonApiMapper\Mappers\DataMapper;
use FreddieGar\JsonApiMapper\Tests\TestCase;

class DataMapperTest extends TestCase
{
    /**
     * @param null $input
     * @return DataMapperInterface|mixed
     */
    protected function dataMapper($input = null)
    {
        return new DataMapper($input);
    }

    public function testDataMapperInvalid()
    {
        foreach (['', null, false, true, 'data', []] as $input) {
            try {
                $this->dataMapper($input);
                $this->assertTrue(false);
            } catch (Exception $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function testDataMapperFromConstructor()
    {
        $data = $this->dataMapper('{}');

        $this->assertInstanceOf(DataMapperInterface::class, $data);
    }

    public function testDataMapperFromLoad()
    {
        $data = $this->dataMapper()->load('{}');

        $this->assertInstanceOf(DataMapperInterface::class, $data);
    }

    private function runTestOnSimple(
        $dataType,
        $dataId,
        $dataAttributes,
        $dataRelationships,
        $dataAttributeName,
        $dataAttributeLanguageId,
        $dataAttributeDescription,
        $dataAttributeCreatedAt,
        $dataAttributeUpdatedAt,
        $dataRelationshipLanguage,
        $dataLinks,
        $dataLinksSelf,
        $dataLinksRelated,
        $dataLinksRelatedHref,
        $dataLinksRelatedMeta,
        $dataLinksRelatedMetaCount,
        $dataAttributeInvalid,
        $dataRelationshipInvalid
    ) {
        $this->assertEquals('users', $dataType);
        $this->assertEquals(1449216560, $dataId);
        $this->assertTrue(is_array($dataAttributes));
        $this->assertTrue(is_array($dataRelationships));
        $this->assertEquals('Jon Doe', $dataAttributeName);
        $this->assertEquals('es', $dataAttributeLanguageId);
        $this->assertEquals(null, $dataAttributeDescription);
        $this->assertEquals('2018-02-14T16:03:43.000Z', $dataAttributeCreatedAt);
        $this->assertEquals('2018-02-14T17:05:35.000Z', $dataAttributeUpdatedAt);
        $this->assertInstanceOf(DataMapperInterface::class, $dataRelationshipLanguage);
        $this->assertInstanceOf(LinksMapperInterface::class, $dataLinks);
        $this->assertEquals('http://example.com/posts/1449216560', $dataLinksSelf);
        $this->assertInstanceOf(RelatedMapperInterface::class, $dataLinksRelated);
        $this->assertEquals('http://example.com/posts/1449216560/comments', $dataLinksRelatedHref);
        $this->assertTrue(is_array($dataLinksRelatedMeta));
        $this->assertEquals(10, $dataLinksRelatedMetaCount);

        $this->assertEquals(null, $dataAttributeInvalid);
        $this->assertEquals(null, $dataRelationshipInvalid);
    }

    public function testDataMapperSimpleGet()
    {
        $data = $this->dataMapper($this->instanceDataSimple());

        $dataType = $data->getType();
        $dataId = $data->getId();
        $dataAttributes = $data->getAttributes();
        $dataRelationships = $data->getRelationships();
        $dataAttributeName = $data->getAttribute('name');
        $dataAttributeLanguageId = $data->getAttribute('language-id');
        $dataAttributeDescription = $data->getAttribute('description');
        $dataAttributeCreatedAt = $data->getAttribute('created-at');
        $dataAttributeUpdatedAt = $data->getAttribute('updated-at');
        $dataRelationshipLanguage = $data->getRelationship('language');
        $dataLinks = $data->getLinks();
        $dataLinksSelf = $data->getLinks()->getSelf();
        $dataLinksRelated = $data->getLinks()->getRelated();
        $dataLinksRelatedHref = $data->getLinks()->getRelated()->getHref();
        $dataLinksRelatedMeta = $data->getLinks()->getRelated()->getMeta();
        $dataLinksRelatedMetaCount = $data->getLinks()->getRelated()->getMeta('count');
        $dataAttributeInvalid = $data->getAttribute('attribute-invalid');
        $dataRelationshipInvalid = $data->getRelationship('relationship-invalid');

        $this->runTestOnSimple(
            $dataType,
            $dataId,
            $dataAttributes,
            $dataRelationships,
            $dataAttributeName,
            $dataAttributeLanguageId,
            $dataAttributeDescription,
            $dataAttributeCreatedAt,
            $dataAttributeUpdatedAt,
            $dataRelationshipLanguage,
            $dataLinks,
            $dataLinksSelf,
            $dataLinksRelated,
            $dataLinksRelatedHref,
            $dataLinksRelatedMeta,
            $dataLinksRelatedMetaCount,
            $dataAttributeInvalid,
            $dataRelationshipInvalid
        );
    }

    public function testDataMapperAlias()
    {
        $data = $this->dataMapper($this->instanceDataSimple());

        $dataType = $data->type();
        $dataId = $data->id();
        $dataAttributes = $data->attributes();
        $dataRelationships = $data->relationships();
        $dataAttributeName = $data->attribute('name');
        $dataAttributeLanguageId = $data->attribute('language-id');
        $dataAttributeDescription = $data->attribute('description');
        $dataAttributeCreatedAt = $data->attribute('created-at');
        $dataAttributeUpdatedAt = $data->attribute('updated-at');
        $dataRelationshipLanguage = $data->relationship('language');
        $dataLinks = $data->links();
        $dataLinksSelf = $data->links()->self();
        $dataLinksRelated = $data->links()->related();
        $dataLinksRelatedHref = $data->links()->related()->href();
        $dataLinksRelatedMeta = $data->links()->related()->meta();
        $dataLinksRelatedMetaCount = $data->links()->related()->meta('count');
        $dataAttributeInvalid = $data->attribute('attribute-invalid');
        $dataRelationshipInvalid = $data->relationship('relationship-invalid');

        $this->runTestOnSimple(
            $dataType,
            $dataId,
            $dataAttributes,
            $dataRelationships,
            $dataAttributeName,
            $dataAttributeLanguageId,
            $dataAttributeDescription,
            $dataAttributeCreatedAt,
            $dataAttributeUpdatedAt,
            $dataRelationshipLanguage,
            $dataLinks,
            $dataLinksSelf,
            $dataLinksRelated,
            $dataLinksRelatedHref,
            $dataLinksRelatedMeta,
            $dataLinksRelatedMetaCount,
            $dataAttributeInvalid,
            $dataRelationshipInvalid
        );
    }

    public function testDataMapperWithAttributesGetAccessors()
    {
        $data = $this->dataMapper($this->instanceDataSimple());

        $dataType = $data->type();
        $dataId = $data->id();
        $dataAttributes = $data->attributes();
        $dataRelationships = $data->relationships();
        $dataAttributeName = $data->getName();
        $dataAttributeLanguageId = $data->getLanguageId();
        $dataAttributeDescription = $data->getDescription();
        $dataAttributeCreatedAt = $data->getCreatedAt();
        $dataAttributeUpdatedAt = $data->getUpdatedAt();
        $dataRelationshipLanguage = $data->relationship('language');
        $dataLinks = $data->links();
        $dataLinksSelf = $data->links()->self();
        $dataLinksRelated = $data->links()->related();
        $dataLinksRelatedHref = $data->links()->related()->href();
        $dataLinksRelatedMeta = $data->links()->related()->meta();
        $dataLinksRelatedMetaCount = $data->links()->related()->meta('count');
        $dataAttributeInvalid = $data->getAttributeInvalid();
        $dataRelationshipInvalid = $data->relationship('relationship-invalid');

        $this->runTestOnSimple(
            $dataType,
            $dataId,
            $dataAttributes,
            $dataRelationships,
            $dataAttributeName,
            $dataAttributeLanguageId,
            $dataAttributeDescription,
            $dataAttributeCreatedAt,
            $dataAttributeUpdatedAt,
            $dataRelationshipLanguage,
            $dataLinks,
            $dataLinksSelf,
            $dataLinksRelated,
            $dataLinksRelatedHref,
            $dataLinksRelatedMeta,
            $dataLinksRelatedMetaCount,
            $dataAttributeInvalid,
            $dataRelationshipInvalid
        );
    }

    public function testDataMapperMethodWithoutAttributesAndRelationshipAccessorsMagic()
    {
        $data = $this->dataMapper($this->instanceDataSimple());

        $dataType = $data->type();
        $dataId = $data->id();
        $dataAttributes = $data->attributes();
        $dataRelationships = $data->relationships();
        $dataAttributeName = $data->name();
        $dataAttributeLanguageId = $data->languageId();
        $dataAttributeDescription = $data->description();
        $dataAttributeCreatedAt = $data->createdAt();
        $dataAttributeUpdatedAt = $data->updatedAt();
        $dataRelationshipLanguage = $data->relationship('language');
        $dataLinks = $data->links();
        $dataLinksSelf = $data->links()->self();
        $dataLinksRelated = $data->links()->related();
        $dataLinksRelatedHref = $data->links()->related()->href();
        $dataLinksRelatedMeta = $data->links()->related()->meta();
        $dataLinksRelatedMetaCount = $data->links()->related()->meta('count');
        $dataAttributeInvalid = $data->attributeInvalid();
        $dataRelationshipInvalid = $data->relationship('relationship-invalid');

        $this->runTestOnSimple(
            $dataType,
            $dataId,
            $dataAttributes,
            $dataRelationships,
            $dataAttributeName,
            $dataAttributeLanguageId,
            $dataAttributeDescription,
            $dataAttributeCreatedAt,
            $dataAttributeUpdatedAt,
            $dataRelationshipLanguage,
            $dataLinks,
            $dataLinksSelf,
            $dataLinksRelated,
            $dataLinksRelatedHref,
            $dataLinksRelatedMeta,
            $dataLinksRelatedMetaCount,
            $dataAttributeInvalid,
            $dataRelationshipInvalid
        );
    }

    public function testDataMapperPropertySnakeAccessors()
    {
        $data = $this->dataMapper($this->instanceDataSimple());

        $dataType = $data->type;
        $dataId = $data->id;
        $dataAttributes = $data->attributes;
        $dataRelationships = $data->relationships;
        $dataAttributeName = $data->name;
        $dataAttributeLanguageId = $data->language_id;
        $dataAttributeDescription = $data->description;
        $dataAttributeCreatedAt = $data->created_At;
        $dataAttributeUpdatedAt = $data->updated_At;
        $dataRelationshipLanguage = $data->relationship->language;
        $dataLinks = $data->links;
        $dataLinksSelf = $data->links->self;
        $dataLinksRelated = $data->links->related;
        $dataLinksRelatedHref = $data->links->related->href;
        $dataLinksRelatedMeta = $data->links->related->meta;
        $dataLinksRelatedMetaCount = $data->links->related->meta('count');
        $dataAttributeInvalid = $data->attributeInvalid;
        $dataRelationshipInvalid = $data->relationship->relationship_invalid;

        $this->runTestOnSimple(
            $dataType,
            $dataId,
            $dataAttributes,
            $dataRelationships,
            $dataAttributeName,
            $dataAttributeLanguageId,
            $dataAttributeDescription,
            $dataAttributeCreatedAt,
            $dataAttributeUpdatedAt,
            $dataRelationshipLanguage,
            $dataLinks,
            $dataLinksSelf,
            $dataLinksRelated,
            $dataLinksRelatedHref,
            $dataLinksRelatedMeta,
            $dataLinksRelatedMetaCount,
            $dataAttributeInvalid,
            $dataRelationshipInvalid
        );
    }

    public function testDataMapperPropertyCamelAccessors()
    {
        $data = $this->dataMapper($this->instanceDataSimple());

        $dataType = $data->type;
        $dataId = $data->id;
        $dataAttributes = $data->attributes;
        $dataRelationships = $data->relationships;
        $dataAttributeName = $data->name;
        $dataAttributeLanguageId = $data->languageId;
        $dataAttributeDescription = $data->description;
        $dataAttributeCreatedAt = $data->createdAt;
        $dataAttributeUpdatedAt = $data->updatedAt;
        $dataRelationshipLanguage = $data->relationship->language;
        $dataLinks = $data->links;
        $dataLinksSelf = $data->links->self;
        $dataLinksRelated = $data->links->related;
        $dataLinksRelatedHref = $data->links->related->href;
        $dataLinksRelatedMeta = $data->links->related->meta;
        $dataLinksRelatedMetaCount = $data->links->related->meta('count');
        $dataAttributeInvalid = $data->attributeInvalid;
        $dataRelationshipInvalid = $data->relationship->relationship_invalid;

        $this->runTestOnSimple(
            $dataType,
            $dataId,
            $dataAttributes,
            $dataRelationships,
            $dataAttributeName,
            $dataAttributeLanguageId,
            $dataAttributeDescription,
            $dataAttributeCreatedAt,
            $dataAttributeUpdatedAt,
            $dataRelationshipLanguage,
            $dataLinks,
            $dataLinksSelf,
            $dataLinksRelated,
            $dataLinksRelatedHref,
            $dataLinksRelatedMeta,
            $dataLinksRelatedMetaCount,
            $dataAttributeInvalid,
            $dataRelationshipInvalid
        );
    }

    public function testDataMapperMultiple()
    {
        $dataMultiple = $this->dataMapper($this->instanceDataMultiple());
        $count = $dataMultiple->count();
        $this->assertEquals(4, $count);

        for ($i = 0; $i < $count; ++$i) {
            $data = $dataMultiple->get($i);
            $this->assertEquals('users', $data->getType());

            switch ($data->getId()) {
                case 1:
                    $this->assertEquals('Jon Doe', $data->getAttribute('name'));
                    $this->assertEquals('es', $data->getAttribute('language-id'));
                    $this->assertEquals(null, $data->getAttribute('description'));
                    $this->assertEquals('2018-02-14T16:03:43.000Z', $data->getAttribute('created-at'));
                    $this->assertEquals('2018-02-14T17:05:35.000Z', $data->getAttribute('updated-at'));
                    break;
                case 2:
                    $this->assertEquals('Sam Doe', $data->getAttribute('name'));
                    $this->assertEquals('es', $data->getAttribute('language-id'));
                    $this->assertEquals('Un-know', $data->getAttribute('description'));
                    $this->assertEquals('2018-03-14T06:13:55.000Z', $data->getAttribute('created-at'));
                    $this->assertEquals(null, $data->getAttribute('updated-at'));
                    break;
                case 3:
                    $this->assertEquals('Steve Jobs', $data->getAttribute('name'));
                    $this->assertEquals('es', $data->getAttribute('language-id'));
                    $this->assertEquals('Engineer', $data->getAttribute('description'));
                    $this->assertEquals('2017-12-24T10:53:43.000Z', $data->getAttribute('created-at'));
                    $this->assertEquals(null, $data->getAttribute('updated-at'));
                    break;
                case 4:
                    $this->assertEquals('', $data->getAttribute('name'));
                    $this->assertEquals('es', $data->getAttribute('language-id'));
                    $this->assertEquals('Literature', $data->getAttribute('description'));
                    $this->assertEquals('2008-02-11T01:01:41.000Z', $data->getAttribute('created-at'));
                    $this->assertEquals(null, $data->getAttribute('updated-at'));
                    break;
                default:
                    $this->assertTrue(false, 'Register of [data] not expected');
            }
        }
    }

    public function testDataMapperSimpleEmpty()
    {
        $data = $this->dataMapper('{"data": null}');
        $this->assertInstanceOf(DataMapperInterface::class, $data);
        $this->assertEquals(null, $data->get());
        $this->assertEquals(null, $data->get(0));
    }

    public function testDataMapperMultipleEmpty()
    {
        $dataMultiple = $this->dataMapper('{"data": []}');
        $count = $dataMultiple->count();
        $this->assertInstanceOf(DataMapperInterface::class, $dataMultiple);
        $this->assertEquals(0, $count);
        $this->assertEquals([], $dataMultiple->get());
        $this->assertEquals(null, $dataMultiple->get(0));
    }
}
