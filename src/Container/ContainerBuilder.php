<?php

declare(strict_types=1);

namespace Faker\Core\Container;

use Faker\Core\Implementation;
use Faker\Core\Extension\BarcodeExtension;
use Faker\Core\Extension\BloodExtension;
use Faker\Core\Extension\ColorExtension;
use Faker\Core\Extension\DateTimeExtension;
use Faker\Core\Extension\FileExtension;
use Faker\Core\Extension\NumberExtension;
use Faker\Core\Extension\UuidExtension;
use Faker\Core\Extension\VersionExtension;

/**
 * @experimental This class is experimental and does not fall under our BC promise
 */
final class ContainerBuilder
{
    /**
     * @var array<string, callable|object|string>
     */
    private $definitions = [];

    /**
     * @param callable|object|string $value
     *
     * @throws \InvalidArgumentException
     */
    public function add($value, string $name = null): self
    {
        if (!is_string($value) && !is_callable($value) && !is_object($value)) {
            throw new \InvalidArgumentException(sprintf(
                'First argument to "%s::add()" must be a string, callable or object.',
                self::class
            ));
        }

        if ($name === null) {
            if (is_string($value)) {
                $name = $value;
            } elseif (is_object($value)) {
                $name = get_class($value);
            } else {
                throw new \InvalidArgumentException(sprintf(
                    'Second argument to "%s::add()" is required not passing a string or object as first argument',
                    self::class
                ));
            }
        }

        $this->definitions[$name] = $value;

        return $this;
    }

    public function build(): ContainerInterface
    {
        return new Container($this->definitions);
    }

    /**
     * Get an array with extension that represent the default English
     * functionality.
     */
    public static function defaultExtensions(): array
    {
        return [
            BarcodeExtension::class => Implementation\Barcode::class,
            BloodExtension::class => Implementation\Blood::class,
            ColorExtension::class => Implementation\Color::class,
            DateTimeExtension::class => Implementation\DateTime::class,
            FileExtension::class => Implementation\File::class,
            NumberExtension::class => Implementation\Number::class,
            VersionExtension::class => Implementation\Version::class,
            UuidExtension::class => Implementation\Uuid::class,
        ];
    }

    public static function getDefault(): ContainerInterface
    {
        $instance = new self();

        foreach (self::defaultExtensions() as $id => $definition) {
            $instance->add($definition, $id);
        }

        return $instance->build();
    }
}
