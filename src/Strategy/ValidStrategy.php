<?php

namespace Faker\Core\Strategy;

use Closure;

class ValidStrategy implements StrategyInterface
{
    private ?Closure $validator = null;

    /**
     * @param ?callable(): bool $validator callable that returns a boolean
     * @param int $retries
     */
    public function __construct(?callable $validator, private int $retries = 10000)
    {
        if ($validator === null) {
            $validator = Closure::fromCallable(fn () => true);
        } elseif (!is_callable($validator)) {
            throw new \InvalidArgumentException('valid() only accepts callables as first argument');
        }

        $this->validator = $validator;
    }

    public function generate(string $name, callable $callback)
    {
        $tries = 0;

        do {
            $response = $callback();

            ++$tries;

            if ($tries > $this->retries) {
                throw new \OverflowException(sprintf('Maximum retries of %d reached without finding a valid value', $this->retries));
            }
        } while (!$this->validator->call($this, $response));

        return $response;
    }
}
