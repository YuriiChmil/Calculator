<?php

declare(strict_types=1);

namespace App\test;

class NumberFormatOther implements NumberFormatterInterface
{
    public function format(int $number): string
    {
        return (string)$number;
    }

    public function supports(int $number): bool
    {
        return $number % 3 !== 0 || $number % 5 !== 0;
    }

}