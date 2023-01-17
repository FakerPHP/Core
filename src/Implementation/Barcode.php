<?php

declare(strict_types=1);

namespace Faker\Core\Implementation;

use Faker\Core\Extension\BarcodeExtension;
use Faker\Core\Extension\Helper;
use Faker\Core\Implementation\Calculator;
use Faker\Core\Implementation\Calculator\Ean;

final class Barcode implements BarcodeExtension
{
    private function ean(int $length = 13): string
    {
        $code = Helper::numerify(str_repeat('#', $length - 1));

        return sprintf('%s%s', $code, Ean::checksum($code));
    }

    public function ean13(): string
    {
        return $this->ean();
    }

    public function ean8(): string
    {
        return $this->ean(8);
    }

    public function isbn10(): string
    {
        $code = Helper::numerify(str_repeat('#', 9));

        return sprintf('%s%s', $code, Calculator\Isbn::checksum($code));
    }

    public function isbn13(): string
    {
        $code = '97' . mt_rand(8, 9) . Helper::numerify(str_repeat('#', 9));

        return sprintf('%s%s', $code, Ean::checksum($code));
    }
}
