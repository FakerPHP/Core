<?php

namespace Faker\Core\Implementation\Calculator;

class Iban
{
    /**
     * Generates IBAN Checksum
     *
     * @return string Checksum (numeric string)
     */
    public static function checksum(string $iban): string
    {
        // Move first four digits to end and set checksum to '00'
        $checkString = substr($iban, 4) . substr($iban, 0, 2) . '00';

        // Replace all letters with their number equivalents
        $checkString = preg_replace_callback('/[A-Z]/', fn (array $a) => self::alphaToNumberCallback($a), $checkString);

        // Perform mod 97 and subtract from 98
        $checksum = 98 - self::mod97($checkString);

        return str_pad((string)$checksum, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Convert a letter to a number.
     *
     * @param array<int|string, string> $match
     * @return string
     */
    private static function alphaToNumberCallback(array $match): string
    {
        return (string)self::alphaToNumber($match[0]);
    }

    /**
     * Converts letter to number
     */
    public static function alphaToNumber(string $char): int
    {
        return ord($char) - 55;
    }

    /**
     * Calculates mod97 on a numeric string
     *
     * @param string $number Numeric string
     */
    public static function mod97(string $number): int
    {
        $checksum = (int)$number[0];

        for ($i = 1, $size = strlen($number); $i < $size; ++$i) {
            $checksum = (10 * $checksum + (int)$number[$i]) % 97;
        }

        return $checksum;
    }

    /**
     * Checks whether an IBAN has a valid checksum
     */
    public static function isValid(string $iban): bool
    {
        return self::checksum($iban) === substr($iban, 2, 2);
    }
}
