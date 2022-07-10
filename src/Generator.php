<?php

namespace Faker\Core;

use Faker\Core\Container\ContainerBuilder;
use Faker\Core\Container\ContainerInterface;
use Faker\Core\Extension\BarcodeExtension;
use Faker\Core\Extension\BloodExtension;
use Faker\Core\Extension\ColorExtension;
use Faker\Core\Extension\DateTimeExtension;
use Faker\Core\Extension\Extension;
use Faker\Core\Extension\ExtensionNotFound;
use Faker\Core\Extension\FileExtension;
use Faker\Core\Extension\GeneratorAwareExtension;
use Faker\Core\Extension\NumberExtension;
use Faker\Core\Extension\UuidExtension;
use Faker\Core\Extension\VersionExtension;

/**
 * @mixin BarcodeExtension
 * @mixin BloodExtension
 * @mixin ColorExtension
 * @mixin DateTimeExtension
 * @mixin FileExtension
 * @mixin NumberExtension
 * @mixin VersionExtension
 * @mixin UuidExtension
 */
class Generator
{
    protected array $formatters = [];

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container ?: ContainerBuilder::getDefault();
    }

    /**
     * @template T of Extension\Extension
     *
     * @param class-string<T> $id
     *
     * @return T
     * @throws ExtensionNotFound
     *
     */
    public function ext(string $id): Extension
    {
        if (!$this->container->has($id)) {
            throw new ExtensionNotFound(sprintf(
                'No Faker extension with id "%s" was loaded.',
                $id
            ));
        }

        $extension = $this->container->get($id);

        if ($extension instanceof GeneratorAwareExtension) {
            $extension = $extension->withGenerator($this);
        }

        return $extension;
    }

    /**
     * With the unique generator you are guaranteed to never get the same two
     * values.
     *
     * <code>
     * // will never return twice the same value
     * $faker->unique()->randomElement(array(1, 2, 3));
     * </code>
     *
     * @param bool $reset If set to true, resets the list of existing values
     * @param int $maxRetries Maximum number of retries to find a unique value,
     *                         After which an OverflowException is thrown.
     *
     * @return self A proxy class returning only non-existing values
     * @throws \OverflowException When no unique value can be found by iterating $maxRetries times
     *
     */
    public function unique($reset = false, $maxRetries = 10000)
    {
        if ($reset || $this->uniqueGenerator === null) {
            $this->uniqueGenerator = new UniqueGenerator($this, $maxRetries);
        }

        return $this->uniqueGenerator;
    }

    /**
     * Get a value only some percentage of the time.
     *
     * @param float $weight A probability between 0 and 1, 0 means that we always get the default value.
     *
     * @return self
     */
    public function optional(float $weight = 0.5, $default = null)
    {
        return new ChanceGenerator($this, $weight, $default);
    }

    /**
     * To make sure the value meet some criteria, pass a callable that verifies the
     * output. If the validator fails, the generator will try again.
     *
     * The value validity is determined by a function passed as first argument.
     *
     * <code>
     * $values = array();
     * $evenValidator = function ($digit) {
     *   return $digit % 2 === 0;
     * };
     * for ($i=0; $i < 10; $i++) {
     *   $values []= $faker->valid($evenValidator)->randomDigit;
     * }
     * print_r($values); // [0, 4, 8, 4, 2, 6, 0, 8, 8, 6]
     * </code>
     *
     * @param ?\Closure $validator A function returning true for valid values
     * @param int $maxRetries Maximum number of retries to find a valid value,
     *                              After which an OverflowException is thrown.
     *
     * @return self A proxy class returning only valid values
     * @throws \OverflowException When no valid value can be found by iterating $maxRetries times
     *
     */
    public function valid(?\Closure $validator = null, int $maxRetries = 10000)
    {
        return new ValidGenerator($this, $validator, $maxRetries);
    }

    public function seed($seed = null): void
    {
        if ($seed === null) {
            mt_srand();
        } else {
            mt_srand((int)$seed, MT_RAND_PHP);
        }
    }

    public function format($format, $arguments = [])
    {
        return call_user_func_array($this->getFormatter($format), $arguments);
    }

    /**
     * @param string $format
     *
     * @return callable
     */
    public function getFormatter($format): callable
    {
        if (isset($this->formatters[$format])) {
            return $this->formatters[$format];
        }

        if (method_exists($this, $format)) {
            $this->formatters[$format] = [$this, $format];

            return $this->formatters[$format];
        }

        // "Faker\Core\Barcode->ean13"
        if (preg_match('|^([a-zA-Z0-9\\\]+)->([a-zA-Z0-9]+)$|', $format, $matches)) {
            $this->formatters[$format] = [$this->ext($matches[1]), $matches[2]];

            return $this->formatters[$format];
        }

        throw new \InvalidArgumentException(sprintf('Unknown format "%s"', $format));
    }

    /**
     * Replaces tokens ('{{ tokenName }}') with the result from the token method call
     *
     * @param string $string String that needs to bet parsed
     *
     * @return string
     */
    public function parse($string)
    {
        $callback = function ($matches) {
            return $this->format($matches[1]);
        };

        return preg_replace_callback('/{{\s?(\w+|[\w\\\]+->\w+?)\s?}}/u', $callback, $string);
    }

}
