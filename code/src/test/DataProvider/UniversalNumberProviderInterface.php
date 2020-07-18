<?php

declare(strict_types=1);

namespace App\test\DataProvider;

interface UniversalNumberProviderInterface
{
    public function supports(int $number): bool;

    public function format(int $number): string;
}