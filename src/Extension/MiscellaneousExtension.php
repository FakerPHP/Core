<?php

namespace Faker\Core\Extension;

/**
 * @experimental This interface is experimental and does not fall under our BC promise
 */
interface MiscellaneousExtension extends Extension
{
    /**
     * Return a boolean, true or false.
     *
     * @param int $chanceOfGettingTrue Between 0 (always get false) and 100 (always get true)
     * @example true
     */
    public function boolean(int $chanceOfGettingTrue = 50): bool;

    /**
     * @example 'cfcd208495d565ef66e7dff9f98764da'
     */
    public function md5(): string;

    /**
     * @example 'b5d86317c2a144cd04d0d7c03b2b02666fafadf2'
     */
    public function sha1(): string;

    /**
     * @example '85086017559ccc40638fcde2fecaf295e0de7ca51b7517b6aebeaaf75b4d4654'
     */
    public function sha256(): string;

    /**
     * Returns an Emoji (Unicode character between U+1F600 and U+1F637).
     *
     * @see https://en.wikipedia.org/wiki/Emoji#Unicode_blocks
     */
    public function emoji(): string;
}
