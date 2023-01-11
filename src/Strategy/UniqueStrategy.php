<?php

namespace Faker\Core\Strategy;

class UniqueStrategy implements StrategyInterface
{
    /**
     * Contains all previously generated values, keyed by the Extension's function name.
     *
     * @var array<string, array<string, null>>
     */
    private array $previous = [];

    public function __construct(
        private int $retries,
    )
    {
    }

    public function generate(string $name, callable $callback)
    {
        if (!isset($this->previous[$name])) {
            $this->previous[$name] = [];
        }

        $tries = 0;

        do {
            $response = $callback();

            ++$tries;

            if ($tries > $this->retries) {
                throw new \OverflowException(sprintf('Maximum retries of %d reached without finding a unique value', $this->retries));
            }
        } while (array_key_exists(serialize($response), $this->previous[$name]));

        $this->previous[$name][serialize($response)] = null;

        return $response;
    }
}
