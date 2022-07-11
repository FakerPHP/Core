<?php

namespace Faker\Core\Strategy;

interface StrategyInterface
{
    public function generate(string $name, callable $callback);
}
