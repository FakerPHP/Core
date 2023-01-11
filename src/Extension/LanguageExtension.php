<?php

namespace Faker\Core\Extension;

/**
 * @experimental This interface is experimental and does not fall under our BC promise
 */
interface LanguageExtension extends Extension
{
    /**
     * @example 'fr'
     */
    public function languageCode(): string;

    /**
     * @example 'fr_FR'
     */
    public function locale(): string;
}
