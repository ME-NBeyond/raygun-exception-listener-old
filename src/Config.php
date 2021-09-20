<?php

namespace RaygunFilterParams;

use Raygun4php\RaygunIdentifier;

class Config
{
    private $baseUrl;
    private $apiKey;
    private $proxy;
    private $userTracking;
    private $useAsync;
    private $user;
    private $track;

    /**
     * Config constructor.
     * @param string|null $baseUrl
     * @param string|null $apiKey
     */
    public function __construct(?string $baseUrl, ?string $apiKey)
    {
        $this->track = !empty($baseUrl) && !empty($apiKey);
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->proxy = "";
        $this->userTracking = false;
        $this->useAsync = false;
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

    /**
     * @return bool
     */
    public function getUseAsync(): bool
    {
        return $this->useAsync;
    }

    /**
     * @param bool $useAsync
     */
    public function setUseAsync(bool $useAsync): void
    {
        $this->useAsync = $useAsync;
    }

    /**
     * @param RaygunIdentifier $user
     */
    public function setUser(RaygunIdentifier $user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return bool
     */
    public function isTrack(): bool
    {
        return $this->track;
    }

    /**
     * @param bool $track
     */
    public function setTrack(bool $track): void
    {
        $this->track = $track;
    }
}
