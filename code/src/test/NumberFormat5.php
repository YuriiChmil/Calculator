<?php

declare(strict_types=1);

namespace App\test;

class NumberFormat5 implements NumberFormatterInterface
{
    public function format(int $number): string
    {
        return 'pow';
    }

    public function supports(int $number): bool
    {
        return $number % 5 === 0;
    }

}