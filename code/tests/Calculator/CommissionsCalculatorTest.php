<?php
declare(strict_types=1);

namespace App\Tests\Calculator;

use App\Calculator\CommissionsCalculator;
use App\CardInfoProvider\CardInfoProviderInterface;
use App\CurrencyProvider\CurrencyRateProviderInterface;
use Money\Money;
use PHPUnit\Framework\TestCase;

class CommissionsCalculatorTest extends TestCase
{
    private CardInfoProviderInterface $cardInfo;
    private CurrencyRateProviderInterface $currencyProvider;
    private CommissionsCalculator $calculator;

    public function setUp(): void
    {
        $this->cardInfo = $this->createMock(CardInfoProviderInterface::class);
        $this->currencyProvider = $this->createMock(CurrencyRateProviderInterface::class);
        $this->calculator = new CommissionsCalculator($this->cardInfo, $this->currencyProvider, 0.01, 0.02);
    }

    /**
     * @param Money $money
     * @param float $resultToCompare
     * @param bool $isFromEu
     * @param float $rate
     * @dataProvider dataProviderForCalculator
     */
    public function testCalculate(Money $money, float $resultToCompare, bool $isFromEu, float $rate): void
    {
        $cardBin = 1234;
        $this->cardInfo->expects(self::once())
                       ->method('isCardFromEU')
                       ->with($this->equalTo($cardBin))
                       ->willReturn($isFromEu);

        $this->currencyProvider->expects(self::once())
                               ->method('getRate')
                               ->with($this->equalTo($money->getCurrency()))
                               ->willReturn($rate);

        $this->assertEquals($resultToCompare, $this->calculator->calculate($cardBin, $money));
    }

    public function dataProviderForCalculator(): iterable
    {
        yield[Money::EUR(100), 1, true, 1];
        yield[Money::USD(50), 0.3725782414307, true, 1.342];
        yield[Money::USD(50), 0.68870523415978, false, 1.452];
        yield[Money::GBP(50), 1.1111111111111, false, 0.9];
    }
}
