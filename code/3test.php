<?php

declare(strict_types=1);

use App\test\DataProvider\CompositeAndDataProvider;
use App\test\DataProvider\UniversalNumberProvider;
use App\test\NumberFormatterInterface;
use App\test\UniversalNumberFormat;

require __DIR__ . '/vendor/autoload.php';
$commonProvider = new UniversalNumberProvider(
    fn(int $number) => (string)$number,
    static function (int $number) {
        $numbers = range(1, 10);

        return in_array($number, $numbers, true);
    });
$providerFor149 = new UniversalNumberProvider(
    fn(int $number) => 'joff',
    static function (int $number) {
        $numbers = [1, 4, 9];

        return in_array($number, $numbers, true);
    });
$providerGreater5 = new UniversalNumberProvider(
    fn(int $number) => 'tchoff',
    static function (int $number) {

        return $number > 5;
    });
$compositeDataProvider = new CompositeAndDataProvider(
    [
        $providerFor149,
        $providerGreater5
    ]
);


/**@var NumberFormatterInterface[] $strategies * */
$strategies = [
    new UniversalNumberFormat([
        $compositeDataProvider,
        $providerFor149,
        $providerGreater5,
        $commonProvider
    ])
];

function test1(array $numbers, array $strategies)
{
    $text = [];
    foreach ($numbers as $number) {
        foreach ($strategies as $strategy) {
            if ($strategy->supports($number)) {
                $text[] = $strategy->format($number);
            }
        }
    }

    echo implode('-', $text);

}

test1(range(1, 10), $strategies);
echo PHP_EOL;