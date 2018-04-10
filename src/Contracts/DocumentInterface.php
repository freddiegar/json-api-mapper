<?php

namespace PlacetoPay\JsonApiMapper\Contracts;

interface DocumentInterface
{
    const KEYWORD_LINKS = 'links';
    const KEYWORD_HREF = 'href';
    const KEYWORD_RELATIONSHIPS = 'relationships';
    const KEYWORD_SELF = 'self';
    const KEYWORD_FIRST = 'first';
    const KEYWORD_LAST = 'last';
    const KEYWORD_NEXT = 'next';
    const KEYWORD_PREV = 'prev';
    const KEYWORD_RELATED = 'related';
    const KEYWORD_LINKAGE_DATA = self::KEYWORD_DATA;
    const KEYWORD_TYPE = 'type';
    const KEYWORD_ID = 'id';
    const KEYWORD_ATTRIBUTES = 'attributes';
    const KEYWORD_META = 'meta';
    const KEYWORD_DATA = 'data';
    const KEYWORD_INCLUDED = 'included';
    const KEYWORD_JSON_API = 'jsonapi';
    const KEYWORD_VERSION = 'version';
    const KEYWORD_ERRORS = 'errors';
    const KEYWORD_ERRORS_ID = 'id';
    const KEYWORD_ERRORS_LINKS = self::KEYWORD_LINKS;
    const KEYWORD_ERRORS_STATUS = 'status';
    const KEYWORD_ERRORS_CODE = 'code';
    const KEYWORD_ERRORS_TITLE = 'title';
    const KEYWORD_ERRORS_DETAIL = 'detail';
    const KEYWORD_ERRORS_META = 'meta';
    const KEYWORD_ERRORS_SOURCE = 'source';
    const KEYWORD_ERRORS_ABOUT = 'about';
}