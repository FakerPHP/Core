<?php

namespace Faker\Core\Extension;

/**
 * @experimental This interface is experimental and does not fall under our BC promise
 */
interface CountryExtension extends Extension
{
    public function countryName(): string;
}
