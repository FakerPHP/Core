<?php

declare(strict_types=1);

namespace Faker\Core\Container;

use Faker\Core\Extension\Extension;

/**
 * A simple implementation of a container.
 *
 * @experimental This class is experimental and does not fall under our BC promise
 */
final class Container implements ContainerInterface
{
    /**
     * @var array<string, callable(): Extension|Extension|class-string<Extension>>
     */
    private array $definitions;

    /**
     * @var array<string, mixed>
     */
    private array $services = [];

    /**
     * Create a container object with a set of definitions. The array value MUST
     * produce an object that implements Extension.
     *
     * @param array<string, callable(): Extension|Extension|class-string<Extension>> $definitions
     */
    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * Retrieve a definition from the container.
     *
     * @param string $id
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws ContainerException
     * @throws NotInContainerException
     */
    public function get(string $id): Extension
    {
        if (array_key_exists($id, $this->services)) {
            return $this->services[$id];
        }

        if (!$this->has($id)) {
            throw new NotInContainerException(sprintf(
                'There is not service with id "%s" in the container.',
                $id
            ));
        }

        $definition = $this->definitions[$id];

        $service = $this->services[$id] = $this->getService($id, $definition);

        if (!$service instanceof Extension) {
            throw new \RuntimeException(sprintf(
                'Service resolved for identifier "%s" does not implement the %s" interface.',
                $id,
                Extension::class
            ));
        }

        return $service;
    }

    /**
     * Get the service from a definition.
     *
     * @template T of \Faker\Core\Extension\Extension
     *
     * @param callable(): T|T|class-string<T> $definition
     *
     * @return T
     */
    private function getService(string $id, callable|object|string $definition): object
    {
        if (is_callable($definition)) {
            try {
                return $definition();
            } catch (\Throwable $e) {
                throw new ContainerException(
                    sprintf('Error while invoking callable for "%s"', $id),
                    0,
                    $e
                );
            }
        } elseif (is_object($definition)) {
            return $definition;
        } elseif (is_string($definition)) {
            if (class_exists($definition)) {
                try {
                    return new $definition();
                } catch (\Throwable $e) {
                    throw new ContainerException(sprintf('Could not instantiate class "%s"', $id), 0, $e);
                }
            }

            throw new ContainerException(sprintf(
                'Could not instantiate class "%s". Class was not found.',
                $id
            ));
        } else {
            throw new ContainerException(sprintf(
                'Invalid type for definition with id "%s"',
                $id
            ));
        }
    }

    /**
     * Check if the container contains a given identifier.
     *
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->definitions);
    }

    /**
     * Get the bindings between Extension interfaces and implementations.
     *
     * @return array<string, callable(): Extension|Extension|class-string<Extension>>
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }
}
