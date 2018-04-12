<?php

namespace FreddieGar\JsonApiMapper\Contracts;

/**
 * Interface DocumentInterface
 * @package FreddieGar\JsonApiMapper\Contracts
 *
 * Reserved Keywords used in json-api specification
 */
interface DocumentInterface
{
    // Top level
    const KEYWORD_DATA = 'data';
    const KEYWORD_ERRORS = 'errors';
    const KEYWORD_META = 'meta';
    const KEYWORD_JSON_API = 'jsonapi';
    const KEYWORD_INCLUDED = 'included';
    const KEYWORD_LINKS = 'links';

    // Resource
    const KEYWORD_TYPE = 'type';
    const KEYWORD_ID = 'id';
    const KEYWORD_ATTRIBUTES = 'attributes';
    const KEYWORD_RELATIONSHIPS = 'relationships';

    // Links Types
    const KEYWORD_HREF = 'href';
    const KEYWORD_SELF = 'self';
    const KEYWORD_RELATED = 'related';
    const KEYWORD_LINKAGE_DATA = self::KEYWORD_DATA;

    // Pagination Links
    const KEYWORD_FIRST = 'first';
    const KEYWORD_PREV = 'prev';
    const KEYWORD_NEXT = 'next';
    const KEYWORD_LAST = 'last';

    // Json Api
    const KEYWORD_VERSION = 'version';

    // Details Errors
    const KEYWORD_ERRORS_ID = self::KEYWORD_ID;
    const KEYWORD_ERRORS_LINKS = self::KEYWORD_LINKS;
    const KEYWORD_ERRORS_STATUS = 'status';
    const KEYWORD_ERRORS_CODE = 'code';
    const KEYWORD_ERRORS_TITLE = 'title';
    const KEYWORD_ERRORS_DETAIL = 'detail';
    const KEYWORD_ERRORS_META = self::KEYWORD_META;
    const KEYWORD_ERRORS_SOURCE = 'source';
    const KEYWORD_ERRORS_ABOUT = 'about';

    // Specials
    const KEYWORD_ATTRIBUTE = 'attribute';
    const KEYWORD_RELATIONSHIP = 'relationship';
}
