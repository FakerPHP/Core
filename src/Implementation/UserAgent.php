<?php

declare(strict_types=1);

namespace Faker\Core\Implementation;

use Faker\Core\Extension\GeneratorAwareExtension;
use Faker\Core\Extension\GeneratorAwareExtensionTrait;
use Faker\Core\Extension\Helper;
use Faker\Core\Extension\UserAgentExtension;

final class UserAgent implements UserAgentExtension, GeneratorAwareExtension
{
    use GeneratorAwareExtensionTrait;

    /** @var string[] */
    private array $userAgents = ['firefox', 'chrome', 'internetExplorer', 'opera', 'safari', 'msedge'];

    /** @var string[] */
    private array $linuxProcessor = ['i686', 'x86_64'];

    /** @var string[ */
    private array $macProcessor = ['Intel', 'PPC', 'U; Intel', 'U; PPC'];

    /** @var string[] */
    private array $windowsPlatformTokens = [
        'Windows NT 6.2',
        'Windows NT 6.1',
        'Windows NT 6.0',
        'Windows NT 5.2',
        'Windows NT 5.1',
        'Windows NT 5.01',
        'Windows NT 5.0',
        'Windows NT 4.0',
        'Windows 98; Win 9x 4.90',
        'Windows 98',
        'Windows 95',
        'Windows CE',
    ];

    public function userAgent(): string
    {
        return Helper::randomElement($this->userAgents);
    }

    /**
     * @example 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_5) AppleWebKit/5312 (KHTML, like Gecko) Chrome/14.0.894.0 Safari/5312'
     */
    public function chrome(): string
    {
        $saf = Helper::randomNumberBetween(531, 536) . Helper::randomNumberBetween(0, 2);
        $platforms = [
            $this->linuxPlatformToken(),
            $this->windowsPlatformToken(),
            $this->macPlatformToken(),
        ];

        return sprintf(
            'Mozilla/5.0 (%s) AppleWebKit/%d (KHTML, like Gecko) Chrome/%d.0.%d.0 Mobile Safari/%d',
            Helper::randomElement($platforms),
            $saf,
            Helper::randomNumberBetween(36, 40),
            Helper::randomNumberBetween(800, 899),
            $saf,
        );
    }

    /**
     * @example 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.82 Safari/537.36 Edg/99.0.1150.36'
     */
    public function edge(): string
    {
        $saf = Helper::randomNumberBetween(531, 537) . '.' . Helper::randomNumberBetween(0, 2);
        $chrv = Helper::randomNumberBetween(79, 99) . '.0';

        $platforms = [
            sprintf(
                '(%s) AppleWebKit/%s (KHTML, like Gecko) Chrome/%s.%d.%d Safari/%s Edg/%s.%d.%d',
                $this->windowsPlatformToken(),
                $saf,
                $chrv,
                Helper::randomNumberBetween(4000, 4844),
                Helper::randomNumberBetween(10, 99),
                $saf,
                $chrv,
                Helper::randomNumberBetween(1000, 1146),
                Helper::randomNumberBetween(0, 99),
            ),
            sprintf(
                '(%s) AppleWebKit/%s (KHTML, like Gecko) Chrome/%s.%d.%d  Safari/%s Edg/%s.%d.%d',
                $this->macPlatformToken(),
                $saf,
                $chrv,
                Helper::randomNumberBetween(4000, 4844),
                Helper::randomNumberBetween(10, 99),
                $saf,
                $chrv,
                Helper::randomNumberBetween(1000, 1146),
                Helper::randomNumberBetween(0, 99),
            ),
            sprintf(
                '(%s) AppleWebKit/%s (KHTML, like Gecko) Chrome/%s.%d.%d  Safari/%s EdgA/%s.%d.%d',
                $this->linuxPlatformToken(),
                $saf,
                $chrv,
                Helper::randomNumberBetween(4000, 4844),
                Helper::randomNumberBetween(10, 99),
                $saf,
                $chrv,
                Helper::randomNumberBetween(1000, 1146),
                Helper::randomNumberBetween(0, 99),
            ),
            sprintf(
                '(%s) AppleWebKit/%s (KHTML, like Gecko) Version/15.0 EdgiOS/%s.%d.%d Mobile/15E148 Safari/%s',
                $this->iosMobileToken(),
                $saf,
                $chrv,
                Helper::randomNumberBetween(1000, 1146),
                Helper::randomNumberBetween(0, 99),
                $saf,
            ),
        ];

        return sprintf(
            'Mozilla/5.0 %s',
            Helper::randomElement($platforms)
        );
    }

    /**
     * @example 'Mozilla/5.0 (X11; Linuxi686; rv:7.0) Gecko/20101231 Firefox/3.6'
     */
    public function firefox(): string
    {
        $ver = sprintf(
            'Gecko/%s Firefox/%d.0',
            date('Ymd', Helper::randomNumberBetween(strtotime('2010-1-1'), time())),
            Helper::randomNumberBetween(35, 37)
        );

        $platforms = [
            sprintf(
                'Mozilla/5.0 (%s; en-US; rv:1.9.%d.20) %s',
                $this->windowsPlatformToken(),
                Helper::randomNumberBetween(0, 2),
                $ver
            ),
            sprintf(
                'Mozilla/5.0 (%s; rv:%d.0) %s',
                $this->linuxPlatformToken(),
                Helper::randomNumberBetween(5, 7),
                $ver,
            ),
            sprintf(
                'Mozilla/5.0 (%s rv:%d.0) %s',
                $this->macPlatformToken(),
                Helper::randomNumberBetween(2, 6),
                $ver,
            ),
        ];

        return Helper::randomElement($platforms);
    }

    public function safari(): string
    {
        $saf = sprintf(
            '%d.%d.%d',
            Helper::randomNumberBetween(531, 535),
            Helper::randomNumberBetween(1, 50),
            Helper::randomNumberBetween(1, 7),
        );

        if (Helper::randomNumberBetween(0, 1) === 1) {
            $ver = sprintf(
                '%d.%d',
                Helper::randomNumberBetween(4, 5),
                Helper::randomNumberBetween(0, 1),
            );
        } else {
            $ver = sprintf(
                '%d.0.%d',
                Helper::randomNumberBetween(4, 5),
                Helper::randomNumberBetween(1, 5),
            );
        }

        $mobileDevices = [
            'iPhone; CPU iPhone OS',
            'iPad; CPU OS',
        ];

        $platforms = [
            sprintf(
                '(Windows; U; %s) AppleWebKit/%s (KHTML, like Gecko) Version/%s Safari/%s',
                $this->windowsPlatformToken(),
                $saf,
                $ver,
                $saf,
            ),
            sprintf(
                '(%s rv:%d.0; en-US) AppleWebKit/%s (KHTML, like Gecko) Version/%s Safari/%s',
                $this->macPlatformToken(),
                Helper::randomNumberBetween(2, 6),
                $saf,
                $ver,
                $saf,
            ),
            sprintf(
                '(%s %d_%d_%d like Mac OS X; en-US) AppleWebKit/%s (KHTML, like Gecko) Version/%d.0.5 Mobile/8B%d Safari/6%s',
                Helper::randomElement($mobileDevices),
                Helper::randomNumberBetween(7, 8),
                Helper::randomNumberBetween(0, 2),
                Helper::randomNumberBetween(1, 2),
                $saf,
                Helper::randomNumberBetween(3, 4),
                Helper::randomNumberBetween(111, 119),
                $saf,
            ),
        ];

        return sprintf(
            'Mozilla/5.0 %s',
            Helper::randomElement($platforms),
        );
    }

    public function opera(): string
    {
        return sprintf(
            'Opera/%d.%d (%s; en-US) Presto/2.%d.%d Version/%d.00',
            Helper::randomNumberBetween(8, 9),
            Helper::randomNumberBetween(10, 99),
            Helper::randomElement([
                $this->windowsPlatformToken(),
                $this->linuxPlatformToken(),
            ]),
            Helper::randomNumberBetween(8, 12),
            Helper::randomNumberBetween(160, 355),
            Helper::randomNumberBetween(10, 12)
        );
    }

    public function windowsPlatformToken(): string
    {
        return Helper::randomElement($this->windowsPlatformTokens);
    }

    public function macPlatformToken(): string
    {
        return sprintf(
            'Macintosh; %s Mac OS X 10_%d_%d',
            Helper::randomElement($this->macProcessor),
            Helper::randomNumberBetween(5, 8),
            Helper::randomNumberBetween(0, 9),
        );
    }

    public function iosMobileToken(): string
    {
        return sprintf(
            'iPhone; CPU iPhone OS %s like Mac OS X',
            Helper::randomNumberBetween(13, 15) . '_' . Helper::randomNumberBetween(0, 2),
        );
    }

    public function linuxPlatformToken(): string
    {
        return sprintf(
            'X11; Linux %s',
            Helper::randomElement($this->linuxProcessor),
        );
    }
}
