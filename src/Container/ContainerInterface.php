<?php

namespace Faker\Core\Container;

use Psr\Container\ContainerInterface as BaseContainerInterface;

interface ContainerInterface extends BaseContainerInterface
{
    /**
     * Get the bindings between Extension interfaces and implementations.
     *
     * @return array<class-string, class-string>
     */
    public function getDefinitions(): array;
}
