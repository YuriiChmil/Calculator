<?php

declare(strict_types=1);

namespace App\Calculator;

use Money\Money;

interface CommissionsCalculatorInterface
{
    public function calculate(int $cardBin, Money $money): float;
}