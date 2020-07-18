<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
/**@var \App\test\NumberFormatterInterface[] $strategies * */
$strategies = [
   new \App\test\NumberFormat115()
];

function test1(array $numbers, array  $strategies)
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

test1(range(1, 20), $strategies);
echo PHP_EOL;