<?php

namespace Faker\Core\Generator;

use Faker\Core\DefaultGenerator;
use Faker\Core\Extension\Extension;

/**
 * Proxy for other generators, to return only valid values. Works with
 * Faker\DefaultGenerator\Base->valid()
 *
 * @mixin DefaultGenerator
 */
class ValidGenerator
{
    protected $generator;
    protected $validator;
    protected $retries;

    /**
     * @param Extension|DefaultGenerator $generator
     * @param callable|null $validator
     * @param int $retries
     */
    public function __construct($generator, ?callable $validator = null, int $retries = 10000)
    {
        if (null === $validator) {
            $validator = static function () {
                return true;
            };
        } elseif (!is_callable($validator)) {
            throw new \InvalidArgumentException('valid() only accepts callables as first argument');
        }
        $this->generator = $generator;
        $this->validator = $validator;
        $this->retries = $retries;
    }

    public function ext(string $id)
    {
        return new self($this->generator->ext($id), $this->validator, $this->retries);
    }

    /**
     * Catch and proxy all generator calls with arguments but return only valid values
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        $i = 0;

        do {
            $res = call_user_func_array([$this->generator, $name], $arguments);
            ++$i;

            if ($i > $this->retries) {
                throw new \OverflowException(sprintf('Maximum retries of %d reached without finding a valid value', $this->retries));
            }
        } while (!call_user_func($this->validator, $res));

        return $res;
    }
}
