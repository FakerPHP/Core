<?php

declare(strict_types=1);

namespace Faker\Core\Implementation;

use Faker\Core\Extension\NumberExtension;

final class Number implements NumberExtension
{
    public function numberBetween(int $min = 0, int $max = 2147483647): int
    {
        $int1 = min($min, $max);
        $int2 = max($min, $max);

        return mt_rand($int1, $int2);
    }

    public function randomDigit(): int
    {
        return mt_rand(0, 9);
    }

    public function randomDigitNot(int $except): int
    {
        $result = self::numberBetween(0, 8);

        if ($result >= $except) {
            ++$result;
        }

        return $result;
    }

    public function randomFloat(?int $nbMaxDecimals = null, float $min = 0, ?float $max = null): float
    {
        if (null === $nbMaxDecimals) {
            $nbMaxDecimals = $this->randomDigit();
        }

        if (null === $max) {
            $max = $this->randomNumber();

            if ($min > $max) {
                $max = $min;
            }
        }

        if ($min > $max) {
            $tmp = $min;
            $min = $max;
            $max = $tmp;
        }

        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), $nbMaxDecimals);
    }

    public function randomNumber(int $nbDigits = null, bool $strict = false): int
    {
        if (null === $nbDigits) {
            $nbDigits = $this->randomDigitNotZero();
        }
        $max = 10 ** $nbDigits - 1;

        if ($max > mt_getrandmax()) {
            throw new \InvalidArgumentException('randomNumber() can only generate numbers up to mt_getrandmax()');
        }

        if ($strict) {
            return mt_rand(10 ** ($nbDigits - 1), $max);
        }

        return mt_rand(0, $max);
    }
}
