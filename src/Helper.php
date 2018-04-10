<?php

namespace PlacetoPay\JsonApiMapper;

use Dflydev\DotAccessData\Data;

/**
 * Class Helper
 * @package PlacetoPay\JsonApiMapper
 */
class Helper
{
    static public function getFromArray(array $array, $pathKey, $default = null)
    {
        return (new Data($array))->get($pathKey, $default);
    }
}