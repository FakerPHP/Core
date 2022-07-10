<?php

declare(strict_types=1);

namespace Faker\Core\Implementation;

use Faker\Core\Extension\BloodExtension;
use Faker\Core\Extension\Helper;

/**
 * @experimental This class is experimental and does not fall under our BC promise
 */
final class Blood implements BloodExtension
{
    /**
     * @var string[]
     */
    private $bloodTypes = ['A', 'AB', 'B', 'O'];

    /**
     * @var string[]
     */
    private $bloodRhFactors = ['+', '-'];

    public function bloodType(): string
    {
        return Helper::randomElement($this->bloodTypes);
    }

    public function bloodRh(): string
    {
        return Helper::randomElement($this->bloodRhFactors);
    }

    public function bloodGroup(): string
    {
        return sprintf(
            '%s%s',
            $this->bloodType(),
            $this->bloodRh()
        );
    }
}
