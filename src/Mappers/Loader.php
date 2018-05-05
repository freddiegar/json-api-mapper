<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\LoaderInterface;

/**
 * Class Loader
 * @package FreddieGar\JsonApiMapper\Mappers
 */
abstract class Loader implements LoaderInterface
{
    /**
     * @var null|array
     */
    protected $original = null;

    /**
     * @var null|array
     */
    protected $current = null;

    /**
     * Loader constructor.
     * @param object|string|array|null $input
     */
    public function __construct($input = null)
    {
        $this->load($input);
    }

    public function load($input, ?string $tag = null)
    {
        if (is_null($input)) {
            return $this;
        }

        if (is_string($input)) {
            $input = json_decode($input, true);
        } elseif (is_object($input)) {
            $input = json_decode(json_encode($input), true);
        }

        if (is_array($input)) {
            $this->original = isset($input[$tag])
                ? $input[$tag]
                : null;
        } else {
            $this->original = null;
        }

        $this->current = $this->original();

        return $this;
    }

    /**
     * @return string|array|null
     */
    protected function original()
    {
        return $this->original;
    }

    /**
     * @return string|array|null
     */
    protected function current()
    {
        return $this->current;
    }

    public function count(): int
    {
        return is_array($this->original())
            ? count($this->original())
            : 0;
    }

    public function all(): array
    {
        return $this->count() > 0
            ? $this->original()
            : [];
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, $method = sprintf('get%s', ucfirst($name)))) {
            return $this->$method($arguments);
        }

        return null;
    }

    public function __get($name)
    {
        if (method_exists($this, $method = sprintf('get%s', ucfirst($name)))
        ) {
            return $this->{$method}();
        }

        return null;
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function sanitizeName(string $name)
    {
        $temp = strlen($name) > 3 && substr($name, 0, 3) === 'get'
            ? substr($name, 3)
            : $name;

        /**
         * Use get{Attribute} method to get attributes, by example: getTitle(), getAge();
         */
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', ' $0', $temp));

        if (strpos($temp, '_') !== false) {
            $temp = str_replace(['_ ', '_'], ' ', $temp);
        }

        return str_replace(' ', '-', $temp);
    }
}
