<?php

namespace RaygunFilterParams;

use GuzzleHttp\Client as HttpClient;
use Raygun4php\RaygunClient;
use Raygun4php\Transports\GuzzleAsync;
use Raygun4php\Transports\GuzzleSync;
use Throwable;

class DataFilter
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var array
     */
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
        '/creds/i',
        '/licence/i'
    ];

    /**
     * DataFilter constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param Throwable $throwable
     * @param array|null $tags
     * @param array|null $userCustomData
     * @param int|null $timestamp
     */
    public function sendExceptionToRaygun(
        Throwable $throwable,
        array $tags = null,
        array $userCustomData = null,
        int $timestamp = null
    ): void {
        $raygunClient = $this->getRaygunClient($this->config);
        $this->setFilterParams($raygunClient);
        $raygunClient->SendException($throwable, $tags, $userCustomData, $timestamp);
    }

    /**
     * @param int $errCode
     * @param string $errMessage
     * @param string $errFile
     * @param int $errLine
     * @param null $tags
     * @param null $userCustomData
     * @param null $timestamp
     */
    public function sendErrorToRaygun(
        int $errCode,
        string $errMessage,
        string $errFile,
        int $errLine,
        $tags = null,
        $userCustomData = null,
        $timestamp = null
    ): void {
        $raygunClient = $this->getRaygunClient($this->config);
        $this->setFilterParams($raygunClient);
        $raygunClient->SendError($errCode, $errMessage, $errFile, $errLine, $tags, $userCustomData, $timestamp);
    }

    /**
     * @param string $string
     */
    public function addToFilter(string $string): void
    {
        $this->filters[] = '/' . $string . '/i';
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

    /**
     * @param RaygunClient $client
     */
    private function setFilterParams(RaygunClient $client)
    {
        $filtered = [];
        foreach ($this->filters as $filter) {
            $filtered[$filter] = true;
        }
        $client->setFilterParams($filtered);
    }

    /**
     * @param Config $config
     * @return RaygunClient
     */
    private function getRaygunClient(Config $config): RaygunClient
    {
        $httpClient = $this->getHttpClient($config);
        $raygunClient = new RaygunClient($this->getGuzzleTransport($config, $httpClient));

        if ($config->getUserTracking()) {
            $raygunClient->setDisableUserTracking(true);
        }

        if ($config->getUser()) {
            $raygunClient->setUserIdentifier($config->getUser());
        }

        return $raygunClient;
    }

    /**
     * @param Config $config
     * @return HttpClient
     */
    private function getHttpClient(Config $config): HttpClient
    {
        $httpConfig = [
            'base_uri' => $config->getBaseUrl(),
            'headers' => ['X-ApiKey' => $config->getApiKey()]
        ];

        if ($config->getProxy()) {
            $httpConfig['proxy'] = $config->getProxy();
        }

        return new HttpClient($httpConfig);
    }

    /**
     * @param Config $config
     * @param HttpClient $httpClient
     * @return GuzzleAsync|GuzzleSync
     */
    private function getGuzzleTransport(Config $config, HttpClient $httpClient)
    {
        if ($config->getUseAsync()) {
            return new GuzzleAsync($httpClient);
        } else {
            return new GuzzleSync($httpClient);
        }
    }
}
