<?php

namespace FreddieGar\JsonApiMapper\Helpers;

use Dflydev\DotAccessData\Data;

/**
 * Class Helper
 * @package FreddieGar\JsonApiMapper\Helpers
 */
class Helper
{
    public static function getFromArray(?array $array, $pathKey, $default = null)
    {
        return is_array($array)
            ? (new Data($array))->get($pathKey, $default)
            : $default;
    }
}
