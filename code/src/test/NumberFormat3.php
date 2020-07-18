<?php

declare(strict_types=1);

namespace App\test;

class NumberFormat3 implements NumberFormatterInterface
{
    public function format(int $number): string
    {
        return 'pa';
    }

    public function supports(int $number): bool
    {
        return $number % 3 === 0;
    }

}