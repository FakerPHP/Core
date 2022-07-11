<?php

namespace Faker\Core\Strategy;

class SimpleStrategy implements StrategyInterface
{
    public function generate(string $name, callable $callback)
    {
        return $callback();
    }
}
