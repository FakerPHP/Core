<?php

namespace Faker\Core\Strategy;

class ChanceStrategy implements StrategyInterface
{
    public function __construct(
        private float $weight,
        private mixed $default = null,
    )
    {
        if ($this->weight < 0 || $this->weight > 1) {
            throw new \InvalidArgumentException('Weight should be a float between 0 and 1');
        }
    }

    public function generate(string $name, callable $callback)
    {
        if (mt_rand(1, 100) > (100 * $this->weight)) {
            return $this->default;
        }

        return $callback();
    }
}
