<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Calculator\CommissionsCalculatorWithRound;
use App\CardInfoProvider\CardInfoProvider;
use App\Calculator\CommissionsCalculator;
use App\CurrencyProvider\CurrencyRateProvider;
use GuzzleHttp\Client;
use Money\Currency;
use Money\Money;

$client = new Client();
$calculator = new CommissionsCalculator(
    new CardInfoProvider($client),
    new CurrencyRateProvider($client),
    0.01,
    0.02
);
$calculator = new CommissionsCalculatorWithRound($calculator,2);
foreach (explode("\n", file_get_contents($argv[1])) as $row) {
    if (empty($row)) {
        break;
    }
    $data = json_decode($row, true, 512, JSON_THROW_ON_ERROR);
    if (!isset($data['bin'], $data['amount'], $data['currency'])) {
        echo 'Invalid row';
        continue;
    }
    echo $calculator->calculate((int)$data['bin'], new Money($data['amount'], new Currency($data['currency'])));
    echo PHP_EOL;
}