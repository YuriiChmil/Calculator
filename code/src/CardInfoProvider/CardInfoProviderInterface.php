<?php

declare(strict_types=1);

namespace App\CardInfoProvider;

interface CardInfoProviderInterface
{
    public function isCardFromEU($cardBin): bool;
}