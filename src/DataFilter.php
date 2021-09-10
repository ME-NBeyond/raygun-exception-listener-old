<?php

namespace RaygunFilterParams;

use GuzzleHttp\Client as HttpClient;
use Raygun4php\RaygunClient;
use Raygun4php\Transports\GuzzleSync;

class DataFilter
{

    private $filters = [
        '/secret/i',
        '/api_key/i',
        '/apikey/i',
        '/token/i',
        '/auth/i',
        '/card/i',
        '/dns/i',
        '/mac/i',
        '/imei/i',

        '/password/i',
        '/passwd/i',
        '/pwd/i',
        '/email/i',
        '/(?!user(-|_)agent)user/i',
        '/name/i',
        '/address/i',
        '/street/i',
        '/city/i',

        '/identity/i',
        '/credential/i',
        '/creds/i'
    ];

    public function sendToRaygun(\Throwable $throwable, array $config, $tags = null)
    {
        $httpClient = new HttpClient([
            'base_uri' => $config['base_uri'],
            'headers' => ['X-ApiKey' => $config['api_key']]
        ]);
        $transport = new GuzzleSync($httpClient);
        $raygunClient = new RaygunClient($transport);
        $raygunClient->setDisableUserTracking(true);
        $this->setFilterParams($raygunClient);
        $raygunClient->SendException($throwable, $tags);
    }

    public function addToFilter(array $array) : void
    {
        foreach ($array as $value){
            $this->filters[] = $value;
        }
    }

    /**
     * @param string[] $filters
     */
    public function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    /**
     * @return string[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    private function setFilterParams(RaygunClient $client)
    {
        $filtered = [];
        foreach ($this->filters as $filter) {
            $filtered[$filter] = true;
        }
        $client->setFilterParams($filtered);
    }
}
