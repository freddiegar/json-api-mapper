<?php

namespace FreddieGar\JsonApiMapper;

use Dflydev\DotAccessData\Data;

/**
 * Class Helper
 * @package FreddieGar\JsonApiMapper
 */
class Helper
{
    static public function getFromArray(?array $array, $pathKey, $default = null)
    {
        return is_array($array)
            ? (new Data($array))->get($pathKey, $default)
            : $default;
    }
}