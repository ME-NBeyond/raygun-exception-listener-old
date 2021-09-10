<?php


namespace RaygunFilterParams;


class Config
{
    private $baseUrl;
    private $apiKey;
    private $proxy;
    private $userTracking;

    public function __construct(string $baseUrl, string $apiKey){
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->userTracking = false;
        $this->proxy = "";
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getProxy(): string
    {
        return $this->proxy;
    }

    /**
     * @param string $proxy
     */
    public function setProxy(string $proxy): void
    {
        $this->proxy = $proxy;
    }

    /**
     * @return bool
     */
    public function getUserTracking(): bool
    {
        return $this->userTracking;
    }

    /**
     * @param bool $userTracking
     */
    public function setUserTracking(bool $userTracking): void
    {
        $this->userTracking = $userTracking;
    }
}