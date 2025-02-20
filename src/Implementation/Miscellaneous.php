<?php

declare(strict_types=1);

namespace Faker\Core\Implementation;

use Faker\Core\Extension\Helper;
use Faker\Core\Extension\MiscellaneousExtension;

final class Miscellaneous implements MiscellaneousExtension
{
    /**
     * @see https://en.wikipedia.org/wiki/Emoji#Unicode_blocks
     * @var string[]
     */
    private array $emoji = [
        "\u{1F600}", "\u{1F601}", "\u{1F602}", "\u{1F603}",
        "\u{1F604}", "\u{1F605}", "\u{1F606}", "\u{1F607}",
        "\u{1F608}", "\u{1F609}", "\u{1F60A}", "\u{1F60B}",
        "\u{1F60C}", "\u{1F60D}", "\u{1F60E}", "\u{1F60F}",
        "\u{1F610}", "\u{1F611}", "\u{1F612}", "\u{1F613}",
        "\u{1F614}", "\u{1F615}", "\u{1F616}", "\u{1F617}",
        "\u{1F618}", "\u{1F619}", "\u{1F61A}", "\u{1F61B}",
        "\u{1F61C}", "\u{1F61D}", "\u{1F61E}", "\u{1F61F}",
        "\u{1F620}", "\u{1F621}", "\u{1F622}", "\u{1F623}",
        "\u{1F624}", "\u{1F625}", "\u{1F626}", "\u{1F627}",
        "\u{1F628}", "\u{1F629}", "\u{1F62A}", "\u{1F62B}",
        "\u{1F62C}", "\u{1F62D}", "\u{1F62E}", "\u{1F62F}",
        "\u{1F630}", "\u{1F631}", "\u{1F632}", "\u{1F633}",
        "\u{1F634}", "\u{1F635}", "\u{1F636}", "\u{1F637}",
    ];

    public function boolean(int $chanceOfGettingTrue = 50): bool
    {
        return Helper::randomNumberBetween(1, 100) <= $chanceOfGettingTrue;
    }

    public function md5(): string
    {
        return md5((string) Helper::randomNumberBetween());
    }

    public function sha1(): string
    {
        return sha1((string) Helper::randomNumberBetween());
    }

    public function sha256(): string
    {
        return hash('sha256', (string) Helper::randomNumberBetween());
    }

    public function emoji(): string
    {
        return Helper::randomElement($this->emoji);
    }
}