<?php

declare(strict_types=1);

namespace Faker\Core\Extension;

use Faker\Core\DefaultGenerator;

/**
 * @experimental This interface is experimental and does not fall under our BC promise
 */
interface GeneratorAwareExtension extends Extension
{
    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the extension, and MUST return an instance that has the
     * new DefaultGenerator.
     */
    // TODO: make generator more generic, with different generation strategies
    public function withGenerator(DefaultGenerator $generator): Extension;
}
