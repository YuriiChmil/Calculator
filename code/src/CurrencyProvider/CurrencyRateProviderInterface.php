<?php

declare(strict_types=1);

namespace App\CurrencyProvider;

use Money\Currency;

interface CurrencyRateProviderInterface
{
    /**
     * @param Currency $currency
     * @return float
     * @throws CurrencyRateException
     */
    public function getRate(Currency $currency): float;

    public function getBaseCurrency(): Currency;
}