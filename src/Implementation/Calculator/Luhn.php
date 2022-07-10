<?php

namespace Faker\Core\Implementation\Calculator;

/**
 * Utility class for generating and validating Luhn numbers.
 *
 * Luhn algorithm is used to validate credit card numbers, IMEI numbers, and
 * National Provider Identifier numbers.
 *
 * @see http://en.wikipedia.org/wiki/Luhn_algorithm
 */
class Luhn
{
    private static function checksum(string $number): int
    {
        $length = strlen($number);
        $sum = 0;

        for ($i = $length - 1; $i >= 0; $i -= 2) {
            $sum += (int) $number[$i];
        }

        for ($i = $length - 2; $i >= 0; $i -= 2) {
            $sum += array_sum(str_split((string) ((int) $number[$i] * 2)));
        }

        return $sum % 10;
    }

    public static function computeCheckDigit(string $partialNumber): string
    {
        $checkDigit = self::checksum($partialNumber . '0');

        if ($checkDigit === 0) {
            return '0';
        }

        return (string) (10 - $checkDigit);
    }

    /**
     * Checks whether a number (partial number + check digit) is Luhn compliant
     */
    public static function isValid(string $number): bool
    {
        return self::checksum($number) === 0;
    }

    /**
     * Generate a Luhn compliant number.
     */
    public static function generateLuhnNumber(string $partialValue): string
    {
        if (!preg_match('/^\d+$/', $partialValue)) {
            throw new \InvalidArgumentException('Argument should be an integer.');
        }

        return $partialValue . Luhn::computeCheckDigit($partialValue);
    }
}
