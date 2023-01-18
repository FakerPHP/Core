<?php

declare(strict_types=1);

namespace Faker\Core\Implementation;

use Faker\Core\Extension\InternetExtension;

final class Internet implements InternetExtension
{
    public function email(): string
    {
        // TODO: Implement email() method.
    }

    public function username(): string
    {
        // TODO: Implement username() method.
    }

    public function password(int $minLength = 6, int $maxLength = 20): string
    {
        // TODO: Implement password() method.
    }

    public function domainName(): string
    {
        // TODO: Implement domainName() method.
    }

    public function tld(): string
    {
        // TODO: Implement tld() method.
    }

    public function url(): string
    {
        // TODO: Implement url() method.
    }

    public function slug(int $wordCount = 6, bool $variableWordCount = true): string
    {
        // TODO: Implement slug() method.
    }

    public function ipv4(): string
    {
        // TODO: Implement ipv4() method.
    }

    public function ipv6(): string
    {
        // TODO: Implement ipv6() method.
    }

    public function localIpv4(): string
    {
        // TODO: Implement localIpv4() method.
    }

    public static function macAddress(): string
    {
        // TODO: Implement macAddress() method.
    }
}
