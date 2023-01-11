<?php

namespace Faker\Core\Extension;

/**
 * @experimental This interface is experimental and does not fall under our BC promise
 */
interface PaymentExtension extends Extension
{
    /**
     * @example 'MasterCard'
     */
    public function creditCardType(): string;

    /**
     * @example '4485480221084675'
     */
    public function creditCardNumber(string $type = null, bool $formatted = false, string $separator = '-'): string;

    /**
     * @example 04/13
     */
    public function creditCardExpirationDate(bool $inFuture = true): string;

    /**
     * International Bank Account Number (IBAN)
     *
     * @see http://en.wikipedia.org/wiki/International_Bank_Account_Number
     *
     * @param string|null $alpha2    ISO 3166-1 alpha-2 country code
     * @param string $prefix    for generating bank account number of a specific bank
     */
    public function iban(string $alpha2=null, string $prefix = ''): string;

    /**
     * Return the String of a SWIFT/BIC number
     *
     * @example 'RZTIAT22263'
     * @see    http://en.wikipedia.org/wiki/ISO_9362
     */
    public function swiftBicNumber(): string;

    /**
     * @example 'EUR'
     * @see https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
     */
    public function currencyCode(): string;
}
