<?php

declare(strict_types=1);

namespace App\Calculator;

use Money\Money;

class CommissionsCalculatorWithRound implements CommissionsCalculatorInterface
{
    private CommissionsCalculatorInterface $calculator;
    private int $precision;

    public function __construct(CommissionsCalculatorInterface $calculator, int $precision)
    {
        $this->calculator = $calculator;
        $this->precision = $precision;
    }

    public function calculate(int $cardBin, Money $money): float
    {
        return round($this->calculator->calculate($cardBin, $money), $this->precision);
    }

}