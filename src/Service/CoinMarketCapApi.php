<?php

namespace App\Service;

use App\Enum\CryptoSymbol;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinMarketCapApi
{
    const BTC_ID = "1";
    const ETH_ID = "1027";
    const XRP_ID = "52";
    const EUR_CURRENCY_ID = "2790";

    public function __construct(private HttpClientInterface $client,private ParameterBagInterface $parameterBag)
    {
    }

    public function getCoinValueInEuro(string $id,array $coins): ?float
    {
        foreach ($coins as $coin)
        {
            if($coin['id'] === (int)$id)
            {
                return round($coin['quote'][self::EUR_CURRENCY_ID]["price"],2);
            }
        }
        return null;
    }

    public function getCryptoMetadata(string $id): mixed
    {
        $url = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/info?id='. $id;

        $response = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-CMC_PRO_API_KEY: ' . $this->parameterBag->get('cmc_api_key'),
                ],
            ]
        );

        if($response->getStatusCode() > 200)
        {
            throw new \Exception('Erreur API : '. $this->getErrorMessage($response->getStatusCode()));
        }

        $content = json_decode($response->getContent(),true);
        if(count($content['data']) > 0)
        {
            return current($content['data']);
        }
        return null;
    }

    public function getCoinsList(): mixed
    {
        $URL = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?convert_id='. self::EUR_CURRENCY_ID;

        $response = $this->client->request(
            'GET',
            $URL,
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-CMC_PRO_API_KEY: ' . $this->parameterBag->get('cmc_api_key'),
                ],
            ]
        );

        if($response->getStatusCode() > 200)
        {
            throw new \Exception('Erreur API : '. $this->getErrorMessage($response->getStatusCode()));
        }

        $content = json_decode($response->getContent(),true);
        return $content['data'];
    }

    public function getIdCmcFromCryptoSymbol(string $cryptoSymbol): ?string
    {
        return match ($cryptoSymbol) {
            (CryptoSymbol::BTC)->value => self::BTC_ID,
            (CryptoSymbol::ETH)->value => self::ETH_ID,
            (CryptoSymbol::XRP)->value => self::XRP_ID,
            default => null,
        };
    }

    public function getErrorMessage($statusCode): ?string
    {
        return match ($statusCode) {
            400 => "(Bad Request) :  The server could not process the request, likely due to an invalid argument.",
            401 => "401 (Unauthorized) Your request lacks valid authentication credentials, likely an issue with your API Key.",
            402 => "402 (Payment Required) Your API request was rejected due to it being a paid subscription plan with an overdue balance. Pay the balance in the Developer Portal billing tab and it will be enabled.",
            403 => "403 (Forbidden) Your request was rejected due to a permission issue, likely a restriction on the API Key's associated service plan. Here is a convenient map of service plans to endpoints.",
            429 => "429 (Too Many Requests) The API Key's rate limit was exceeded; consider slowing down your API Request frequency if this is an HTTP request throttling error. Consider upgrading your service plan if you have reached your monthly API call credit limit for the day/month.",
            500 => "500 (Internal Server Error) An unexpected server issue was encountered.",
            default => $statusCode . " : This status code is not referenced by CoinMarketCap",
        };
    }
}