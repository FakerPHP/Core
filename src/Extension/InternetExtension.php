<?php

namespace Faker\Core\Extension;

/**
 * @experimental This interface is experimental and does not fall under our BC promise
 */
interface InternetExtension extends Extension
{
    /**
     * @example my-name@example.com
     */
    public function email(): string;

    /**
     * @example 'jdoe'
     */
    public function username(): string;

    /**
     * @example 'fY4èHdZv68'
     */
    public function password(int $minLength = 6, int $maxLength = 20): string;

    /**
     * @example 'tiramisu.com'
     */
    public function domainName(): string;

    /**
     * @example 'com'
     */
    public function tld(): string;

    /**
     * @example 'https://faker.example.com/'
     */
    public function url(): string;

    /**
     * @example 'aut-repellat-commodi-vel-itaque-nihil-id-saepe-nostrum'
     */
    public function slug(int $wordCount = 6, bool $variableWordCount = true): string;

    /**
     * @example '237.149.115.38'
     */
    public function ipv4(): string;

    /**
     * @example '35cd:186d:3e23:2986:ef9f:5b41:42a4:e6f1'
     */
    public function ipv6(): string;

    /**
     * @see https://tools.ietf.org/html/rfc1918#section-3
     * @example '10.1.1.17'
     */
    public function localIpv4(): string;

    /**
     * @example '32:F1:39:2F:D6:18'
     */
    public static function macAddress(): string
}
