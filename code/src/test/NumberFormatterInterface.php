<?php

declare(strict_types=1);

namespace App\test;

interface NumberFormatterInterface
{
    public function format(int $number): string;

    public function supports(int $number): bool;
}