<?php

namespace Faker\Core\Extension;

/**
 * A class with some methods that may make building extensions easier.
 *
 * @experimental This class is experimental and does not fall under our BC promise
 */
final class Helper
{
    /**
     * Returns a random element from a passed array.
     *
     * @param array<int|string, mixed> $array
     */
    public static function randomElement(array $array): mixed
    {
        if ($array === []) {
            return null;
        }

        return $array[array_rand($array, 1)];
    }

    /**
     * Return a boolean, true or false.
     *
     * @param int $chanceOfGettingTrue Between 0 (always get false) and 100 (always get true)
     */
    public static function boolean(int $chanceOfGettingTrue = 50): bool
    {
        return self::numberBetween(1, 100) <= $chanceOfGettingTrue;
    }

    /**
     * Returns a random number between start and end
     */
    public static function numberBetween(int $start = 0, int $end = 2147483647): int
    {
        $min = min($start, $end);
        $max = max($start, $end);

        return random_int($min, $max);
    }

    /**
     * Replaces all hash sign ('#') occurrences with a random number
     * Replaces all percentage sign ('%') occurrences with a non-zero number.
     *
     * @param string $string String that needs to bet parsed
     */
    public static function numerify(string $string): string
    {
        // instead of using randomDigit() several times, which is slow,
        // count the number of hashes and generate once a large number
        $toReplace = [];

        if (($pos = strpos($string, '#')) !== false) {
            for ($i = $pos, $last = strrpos($string, '#', $pos) + 1; $i < $last; ++$i) {
                if ($string[$i] === '#') {
                    $toReplace[] = $i;
                }
            }
        }

        if ($nbReplacements = count($toReplace)) {
            $maxAtOnce = strlen((string) mt_getrandmax()) - 1;
            $numbers = '';
            $i = 0;

            while ($i < $nbReplacements) {
                $size = min($nbReplacements - $i, $maxAtOnce);
                $numbers .= str_pad((string) mt_rand(0, 10 ** $size - 1), $size, '0', STR_PAD_LEFT);
                $i += $size;
            }

            for ($i = 0; $i < $nbReplacements; ++$i) {
                $string[$toReplace[$i]] = $numbers[$i];
            }
        }

        return self::replaceWildcard($string, '%', static function () {
            return mt_rand(1, 9);
        });
    }

    /**
     * Replaces all question mark ('?') occurrences with a random letter.
     *
     * @param string $string String that needs to bet parsed
     */
    public static function lexify(string $string): string
    {
        return self::replaceWildcard($string, '?', static function () {
            return chr(mt_rand(97, 122));
        });
    }

    /**
     * Replaces hash signs ('#') and question marks ('?') with random numbers and letters
     * An asterisk ('*') is replaced with either a random number or a random letter.
     *
     * @param string $string String that needs to bet parsed
     */
    public static function bothify(string $string): string
    {
        $string = self::replaceWildcard($string, '*', static function () {
            return mt_rand(0, 1) ? '#' : '?';
        });

        return Helper::lexify(Helper::numerify($string));
    }

    /**
     * Converts string to lowercase.
     * Uses mb_string extension if available.
     *
     * @param string $string String that should be converted to lowercase
     */
    public static function toLower(string $string): string
    {
        return extension_loaded('mbstring') ? mb_strtolower($string, 'UTF-8') : strtolower($string);
    }

    private static function replaceWildcard(string $string, string $wildcard, callable $callback): string
    {
        if (($pos = strpos($string, $wildcard)) === false) {
            return $string;
        }

        for ($i = $pos, $last = strrpos($string, $wildcard, $pos) + 1; $i < $last; ++$i) {
            if ($string[$i] === $wildcard) {
                $string[$i] = call_user_func($callback);
            }
        }

        return $string;
    }
}
