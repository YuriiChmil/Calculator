<?php

declare(strict_types=1);

namespace App\test;

class NumberFormat3And5 implements NumberFormatterInterface
{
    public function format(int $number): string
    {
        return 'papow';
    }

    public function supports(int $number): bool
    {
        return $number % 3 === 0 && $number % 3 === 5;
    }

}