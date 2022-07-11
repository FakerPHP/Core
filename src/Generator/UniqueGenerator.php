<?php

namespace Faker\Core\Generator;

use Faker\Core\DefaultGenerator;
use Faker\Core\Extension\Extension;

/**
 * Proxy for other generators that returns only unique values.
 *
 * Instantiated through @see DefaultGenerator::unique().
 *
 * @mixin DefaultGenerator
 */
class UniqueGenerator
{
    protected $generator;
    protected int $retries;

    /**
     * Maps from method names to a map with serialized result keys.
     *
     * @example [
     *   'phone' => ['0123' => null],
     *   'city' => ['London' => null, 'Tokyo' => null],
     * ]
     *
     * @var array<string, array<string, null>>
     */
    protected array $uniques = [];

    /**
     * @param Extension|DefaultGenerator $generator
     * @param int $retries
     * @param array<string, array<string, null>> $uniques
     */
    public function __construct($generator, int $retries = 10000, array &$uniques = [])
    {
        $this->generator = $generator;
        $this->retries = $retries;
        $this->uniques = &$uniques;
    }

    public function ext(string $id)
    {
        return new self($this->generator->ext($id), $this->retries, $this->uniques);
    }

    /**
     * Catch and proxy all generator calls with arguments but return only unique values
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        if (!isset($this->uniques[$name])) {
            $this->uniques[$name] = [];
        }
        $i = 0;

        do {
            $res = call_user_func_array([$this->generator, $name], $arguments);
            ++$i;

            if ($i > $this->retries) {
                throw new \OverflowException(sprintf('Maximum retries of %d reached without finding a unique value', $this->retries));
            }
        } while (array_key_exists(serialize($res), $this->uniques[$name]));
        $this->uniques[$name][serialize($res)] = null;

        return $res;
    }
}
