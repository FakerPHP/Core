<?php

declare(strict_types=1);

namespace Faker\Core\Implementation;

use Faker\Core\Extension\DateTimeExtension;
use Faker\Core\Extension\GeneratorAwareExtensionTrait;
use Faker\Core\Extension\Helper;
use Faker\Core\Extension\PaymentExtension;
use Faker\Core\Implementation\Calculator\Luhn;

/**
 * @experimental This class is experimental and does not fall under our BC promise
 */
final class Payment implements PaymentExtension
{
    use GeneratorAwareExtensionTrait;

    /** @var string[]  */
    private array $cardVendors = [
        'Visa', 'MasterCard', 'American Express', 'Discover Card', 'Visa Retired', 'JCB',
    ];

    private array $cardParams = [
        'Visa' => [
            '4539###########',
            '4556###########',
            '4916###########',
            '4532###########',
            '4929###########',
            '40240071#######',
            '4485###########',
            '4716###########',
            '4##############',
        ],
        'Visa Retired' => [
            '4539########',
            '4556########',
            '4916########',
            '4532########',
            '4929########',
            '40240071####',
            '4485########',
            '4716########',
            '4###########',
        ],
        'MasterCard' => [
            '2221###########',
            '23#############',
            '24#############',
            '25#############',
            '26#############',
            '2720###########',
            '51#############',
            '52#############',
            '53#############',
            '54#############',
            '55#############',
        ],
        'American Express' => [
            '34############',
            '37############',
        ],
        'Discover Card' => [
            '6011###########',
        ],
        'JCB' => [
            '3528###########',
            '3589###########',
        ],
    ];

    /** @var string[]  */
    private array $currencyCodes = [
        'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN',
        'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BRL',
        'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY',
        'COP', 'CRC', 'CUC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD',
        'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GHS', 'GIP',
        'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HTG', 'HUF', 'IDR', 'ILS',
        'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR',
        'KMF', 'KPW', 'KRW', 'KWD', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD',
        'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRU',
        'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK',
        'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG',
        'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK',
        'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'SSP', 'STN', 'SYP', 'SZL', 'THB',
        'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX',
        'USD', 'UYU', 'UZS', 'VES', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF',
        'XPF', 'YER', 'ZAR', 'ZMW',
    ];

    public function creditCardType(): string
    {
        return Helper::randomElement($this->cardVendors);
    }

    public function creditCardNumber(string $type = null, bool $formatted = false, string $separator = '-'): string
    {
        if (null === $type) {
            $type = $this->creditCardType();
        }
        $mask = Helper::randomElement($this->cardParams[$type]);

        $number = Helper::numerify($mask);
        $number .= Luhn::computeCheckDigit($number);

        if ($formatted) {
            $p1 = substr($number, 0, 4);
            $p2 = substr($number, 4, 4);
            $p3 = substr($number, 8, 4);
            $p4 = substr($number, 12);
            $number = $p1 . $separator . $p2 . $separator . $p3 . $separator . $p4;
        }

        return $number;
    }

    public function creditCardExpirationDate(bool $inFuture = true): string
    {
        /** @var DateTimeExtension $dateExtension */
        $dateExtension = $this->generator->ext(DateTimeExtension::class);

        if ($inFuture) {
            return $dateExtension->dateTimeBetween(
                'now',
                '36 months',
            )->format('m/Y');
        }


        return $dateExtension->dateTimeBetween(
            '-36 months',
            '36 months',
        )->format('m/Y');
    }

    public function iban(string $alpha2 = null, string $prefix = ''): string
    {
        // @todo
    }

    public function swiftBicNumber(): string
    {
        // @todo
    }

    public function currencyCode(): string
    {
        return Helper::randomElement($this->currencyCodes);
    }
}
