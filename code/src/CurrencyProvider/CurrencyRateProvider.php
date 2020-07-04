<?php

declare(strict_types=1);

namespace App\CurrencyProvider;

use GuzzleHttp\Psr7\Request;
use Money\Currency;
use Psr\Http\Client\ClientInterface;

class CurrencyRateProvider implements CurrencyRateProviderInterface
{
    private const URL_PROVIDER = 'https://api.exchangeratesapi.io/latest';

    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getRate(Currency $currency): float
    {
        if ($currency->equals($this->getBaseCurrency())) {
            return 1.0;
        }
        $request = new Request('GET', static::URL_PROVIDER);
        $response = $this->client->sendRequest($request);
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(sprintf('Unable to get currency rate from %s', static::URL_PROVIDER));
        }
        $decodeResponse = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $rate = $decodeResponse['rates'][$currency->getCode()] ?? null;
        if ($rate === null) {
            throw new CurrencyRateException(sprintf('Unable to get currency rate for %s', $currency->getCode()));
        }

        return (float)$rate;
    }

    public function getBaseCurrency(): Currency
    {
        return new Currency('EUR');
    }

}