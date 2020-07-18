<?php

declare(strict_types=1);

namespace App\test;

class NumberFormat115 implements NumberFormatterInterface
{
    public function format(int $number): string
    {
        if ($number % 2 === 0 && $number % 7 === 0) {
            return 'hateeho';
        }
        if ($number % 2 === 0) {
            return 'hatee';
        }

        if ($number % 7 === 0) {
            return 'ho';
        }

        return (string)$number;
    }

    public function supports(int $number): bool
    {
        $data = range(1, 15);

        return in_array($number, $data, true);
    }

}