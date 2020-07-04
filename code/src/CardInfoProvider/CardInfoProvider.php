<?php

declare(strict_types=1);

namespace App\CardInfoProvider;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;

class CardInfoProvider implements CardInfoProviderInterface
{
    private const URL_PROVIDER = 'https://lookup.binlist.net';
    private const EU_LIST = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function isCardFromEU($cardBin): bool
    {
        $request = new Request('GET', sprintf('%s/%s', static::URL_PROVIDER, $cardBin));
        $response = $this->client->sendRequest($request);
        $decodeResponse = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(sprintf('Unable to get card data from %s', static::URL_PROVIDER));
        }

        return in_array($decodeResponse->country->alpha2, static::EU_LIST, true);
    }

}