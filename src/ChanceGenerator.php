<?php

namespace Faker\Core;

use Faker\Core\Extension\Extension;

/**
 * This generator returns a default value for all called properties
 * and methods. It works with Faker\Generator::optional().
 *
 * @mixin Generator
 */
class ChanceGenerator
{
    private $generator;
    private $weight;
    protected $default;

    /**
     * @param Extension|Generator $generator
     */
    public function __construct($generator, float $weight, $default = null)
    {
        $this->default = $default;
        $this->generator = $generator;
        $this->weight = $weight;
    }

    public function ext(string $id)
    {
        return new self($this->generator->ext($id), $this->weight, $this->default);
    }

    /**
     * @param string $name
     * @param array  $arguments
     */
    public function __call($name, $arguments)
    {
        if (mt_rand(1, 100) <= (100 * $this->weight)) {
            return call_user_func_array([$this->generator, $name], $arguments);
        }

        return $this->default;
    }
}
