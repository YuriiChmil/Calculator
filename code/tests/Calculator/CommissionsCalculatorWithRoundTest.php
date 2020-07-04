<?php

declare(strict_types=1);

namespace App\Tests\Calculator;

use App\Calculator\CommissionsCalculatorInterface;
use App\Calculator\CommissionsCalculatorWithRound;
use Money\Money;
use PHPUnit\Framework\TestCase;

class CommissionsCalculatorWithRoundTest extends TestCase
{
    private CommissionsCalculatorInterface $calculator;
    private CommissionsCalculatorWithRound $calculatorWithRound;

    public function setUp(): void
    {
        $this->calculator = $this->createMock(CommissionsCalculatorInterface::class);
        $this->calculatorWithRound = new CommissionsCalculatorWithRound($this->calculator, 2);
    }

    /**
     * @param float $resultToCompare
     * @param float $resultWithoutRound
     * @dataProvider dataProviderForCalculator
     */
    public function testCalculate(float $resultToCompare, float $resultWithoutRound): void
    {
        $this->calculator->method('calculate')->willReturn($resultWithoutRound);

        $result = $this->calculatorWithRound->calculate(234, Money::GBP(100));

        $this->assertEquals($resultToCompare, $result);
    }

    public function dataProviderForCalculator(): iterable
    {
        yield [1.23, 1.2345];
        yield [0.23, 0.2345];
        yield [3.68, 3.676666666666];
    }
}