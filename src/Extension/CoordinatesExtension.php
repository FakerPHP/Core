<?php

namespace Faker\Core\Extension;

/**
 * @experimental This interface is experimental and does not fall under our BC promise
 */
interface CoordinatesExtension extends Extension
{
    /**
     * @example 'fr'
     */
    public function latitude(float $min = -90.0, float $max = 90.0): float;

    /**
     * @example '86.211205'
     *
     * @return float Uses signed degrees format (returns a float number between -180 and 180)
     */
    public function longitude(float $min = -180.0, float $max = 180.0): float;

    /**
     * @example array('77.147489', '86.211205')
     *
     * @return array{latitude: float, longitude: float}
     */
    public function localCoordinates(): array;
}
