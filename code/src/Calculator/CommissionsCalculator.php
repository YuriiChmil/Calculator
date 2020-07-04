<?php

declare(strict_types=1);

namespace App\Calculator;

use App\CardInfoProvider\CardInfoProviderInterface;
use App\CurrencyProvider\CurrencyRateProviderInterface;
use Money\Currency;
use Money\Money;

class CommissionsCalculator implements CommissionsCalculatorInterface
{
    private CardInfoProviderInterface $cardInfoProvider;
    private CurrencyRateProviderInterface $currencyRateProvider;
    private float $euCommission;
    private float $noEuCommission;

    public function __construct(
        CardInfoProviderInterface $cardInfoProvider,
        CurrencyRateProviderInterface $currencyRateProvider,
        float $euCommission,
        float $noEuCommission
    ) {
        $this->cardInfoProvider = $cardInfoProvider;
        $this->currencyRateProvider = $currencyRateProvider;
        $this->euCommission = $euCommission;
        $this->noEuCommission = $noEuCommission;
    }

    public function calculate(int $cardBin, Money $money): float
    {
        $rate = $this->currencyRateProvider->getRate($money->getCurrency());
        $amount = (float)$money->getAmount();
        if ($rate === (float)0) {
            $convertedAmount = $amount;
        } else {
            $convertedAmount = $amount / $rate;
        }

        return $convertedAmount * $this->getCommissionRate($cardBin);
    }

    private function getCommissionRate($cardBin): float
    {
        if ($this->cardInfoProvider->isCardFromEU($cardBin)) {
            return $this->euCommission;
        }

        return $this->noEuCommission;
    }
}