<?php

declare(strict_types=1);

namespace App\Tests\CurrencyRateProvider;

use App\CurrencyProvider\CurrencyRateException;
use App\CurrencyProvider\CurrencyRateProvider;
use GuzzleHttp\Psr7\Request;
use Money\Currency;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class CurrencyRateProviderTest extends TestCase
{
    private ClientInterface $client;
    private CurrencyRateProvider $rateProvider;

    public function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->rateProvider = new CurrencyRateProvider($this->client);
    }

    public function testGetRateWithDefaultCurrency(): void
    {
        $rate = $this->rateProvider->getRate(new Currency('EUR'));
        $this->client->expects(self::never())->method('sendRequest');
        $this->assertSame(1.0, $rate);
    }

    /**
     * @param Currency $currency
     * @param float $expectedResult
     * @throws CurrencyRateException
     * @dataProvider rateDataProvider
     */
    public function testGetRate(Currency $currency, float $expectedResult): void
    {
        $request = $this->getRequest();
        $response = $this->getResponseMock();

        $this->client->expects(self::once())->method('sendRequest')
                     ->with($this->equalTo($request))
                     ->willReturn($response);

        $this->assertSame($expectedResult, $this->rateProvider->getRate($currency));
    }

    private function getRequest(): Request
    {
        return new Request('GET', 'https://api.exchangeratesapi.io/latest');
    }

    private function getResponseMock(int $status = 200): ResponseInterface
    {
        $streamMock = $this->createMock(StreamInterface::class);
        $responseString = '{"rates":{"CAD":1.5233,"USD":1.1224,"MXN":25.3049,"GBP":0.9012},"base":"EUR","date":"2020-07-03"}';

        $streamMock->method('getContents')->willReturn($responseString);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn($status);
        $response->method('getBody')->willReturn($streamMock);

        return $response;
    }


    public function testGetRateWithUndefinedCurrency(): void
    {
        $request = $this->getRequest();
        $response = $this->getResponseMock();

        $this->client->expects(self::once())->method('sendRequest')
                     ->with($this->equalTo($request))
                     ->willReturn($response);

        $this->expectException(CurrencyRateException::class);
        $this->rateProvider->getRate(new Currency('UAH'));
    }

    /**
     * @param int $status
     * @throws CurrencyRateException
     * @dataProvider statusDataProvider
     */
    public function testGetRateWhenServiceUnavailable(int $status): void
    {
        $request = $this->getRequest();
        $response = $this->getResponseMock($status);

        $this->client->expects(self::once())->method('sendRequest')
                     ->with($this->equalTo($request))
                     ->willReturn($response);

        $this->expectException(RuntimeException::class);
        $this->rateProvider->getRate(new Currency('USD'));
    }

    public function statusDataProvider():iterable
    {
        yield [500];
        yield [400];
        yield [422];
        yield [401];
        yield [403];
        yield [502];
        yield [503];
    }

    public function rateDataProvider(): iterable
    {
        yield [new Currency('CAD'), 1.5233];
        yield [new Currency('USD'), 1.1224];
        yield [new Currency('MXN'), 25.3049];
    }

    public function testGetBaseCurrency(): void
    {
        $this->assertEquals(new Currency('EUR'), $this->rateProvider->getBaseCurrency());
    }
}