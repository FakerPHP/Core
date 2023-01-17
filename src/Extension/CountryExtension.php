<?php

namespace Faker\Core\Extension;

/**
 * @experimental This interface is experimental and does not fall under our BC promise
 */
interface CountryExtension extends Extension
{
    /**
     * @example 'FR'
     * @see https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
     */
    public function countryISOAlpha2(): string;

    /**
     * @example 'FRA'
     * @see https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3
     */
    public function countryISOAlpha3(): string;
}
