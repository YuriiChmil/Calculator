<?php

declare(strict_types=1);

namespace App\Tests\CardInfoProvider;

use App\CardInfoProvider\CardInfoProvider;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class CardInfoProviderTest extends TestCase
{
    private ClientInterface $client;
    private CardInfoProvider $cardInfoProvider;

    public function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->cardInfoProvider = new CardInfoProvider($this->client);
    }

    public function isFromEuDataProvider(): iterable
    {
        yield ['AT', true];
        yield ['BE', true];
        yield ['BG', true];
        yield ['CY', true];
        yield ['CZ', true];
        yield ['DE', true];
        yield ['DK', true];
        yield ['EE', true];
        yield ['ES', true];
        yield ['FI', true];
        yield ['FR', true];
        yield ['GR', true];
        yield ['HR', true];
        yield ['HU', true];
        yield ['IE', true];
        yield ['IT', true];
        yield ['LT', true];
        yield ['LU', true];
        yield ['LV', true];
        yield ['MT', true];
        yield ['NL', true];
        yield ['PO', true];
        yield ['PT', true];
        yield ['RO', true];
        yield ['SE', true];
        yield ['SI', true];
        yield ['SK', true];
        yield ['US', false];
        yield ['UK', false];
    }

    /**
     * @param string $countryCode
     * @param int $cardBin
     * @param bool $resultToCompare
     * @throws \JsonException
     * @dataProvider isFromEuDataProvider
     */
    public function testIsCardFromEU(string $countryCode, bool $resultToCompare): void
    {
        $cardBin = 1234;
        $request = new Request('GET', sprintf('%s/%s', 'https://lookup.binlist.net', $cardBin));
        $response = $this->getResponseMock($countryCode, 200);
        $this->client->expects(self::once())->method('sendRequest')->with($this->equalTo($request))->willReturn($response);

        $this->assertSame($resultToCompare, $this->cardInfoProvider->isCardFromEU($cardBin));
    }

    public function statusDataProvider(): iterable
    {
        yield [500];
        yield [400];
        yield [422];
        yield [401];
        yield [403];
        yield [502];
        yield [503];
    }

    /**
     * @param int $status
     * @throws \JsonException
     * @dataProvider statusDataProvider
     */
    public function testGetRateWhenServiceUnavailable(int $status): void
    {
        $cardBin = 1234;
        $request = new Request('GET', sprintf('%s/%s', 'https://lookup.binlist.net', $cardBin));
        $response = $this->getResponseMock('UK', $status);

        $this->client->expects(self::once())->method('sendRequest')
                     ->with($this->equalTo($request))
                     ->willReturn($response);

        $this->expectException(RuntimeException::class);
        $this->cardInfoProvider->isCardFromEU($cardBin);
    }


    private function getResponseMock(string $countryCode, int $status = 200): ResponseInterface
    {
        $streamMock = $this->createMock(StreamInterface::class);
        $responseString = json_encode(['country' => ['alpha2' => $countryCode]], JSON_THROW_ON_ERROR);

        $streamMock->method('getContents')->willReturn($responseString);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn($status);
        $response->method('getBody')->willReturn($streamMock);

        return $response;
    }
}