<?php

declare(strict_types=1);

namespace Faker\Core\Extension;


use Faker\Core\DefaultGenerator;

/**
 * A helper trait to be used with GeneratorAwareExtension.
 */
trait GeneratorAwareExtensionTrait
{
    /**
     * @var DefaultGenerator
     */
    private $generator;

    /**
     * @return static
     */
    public function withGenerator(DefaultGenerator $generator): Extension
    {
        $instance = clone $this;

        $instance->generator = $generator;

        return $instance;
    }
}
