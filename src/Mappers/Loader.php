<?php

namespace FreddieGar\JsonApiMapper\Mappers;

use FreddieGar\JsonApiMapper\Contracts\LoaderInterface;

abstract class Loader implements LoaderInterface
{
    /**
     * @var array
     */
    protected $original = [];

    /**
     * @var array
     */
    protected $current = [];

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
            $input = $input = json_decode($input, true);
        } elseif (is_object($input)) {
            $input = json_decode(json_encode($input), true);
        }

        $this->original = is_array($input) && isset($input[$tag])
            ? $input[$tag]
            : [];

        $this->current = $this->original();

        return $this;
    }

    protected function original(): array
    {
        return $this->original;
    }

    protected function current(): array
    {
        return $this->current;
    }

    public function count(): int
    {
        return count($this->original());
    }

//    public function __call($name, $arguments)
//    {
//        if (method_exists($this, $method = sprintf('get%d', $name))) {
//            return $this->$method($arguments);
//        }
//    }
}
