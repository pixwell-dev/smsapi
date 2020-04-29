<?php

declare(strict_types=1);

namespace NotificationChannels\Smsapi;

class SmsapiConfig
{
    /** @var array */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getAuthToken(): ?string
    {
        return $this->config['auth_token'] ?? null;
    }

    public function getApiService(): ?string
    {
        return $this->config['service'] ?? null;
    }

    public function usingComService(): bool
    {
        return $this->getApiService() === 'com';
    }

    public function usingPlService(): bool
    {
        return $this->getApiService() === 'pl';
    }

    public function isTest(): bool
    {
        return $this->config['defaults']['common']['test'] ?? false;
    }

    public function getSmsDefaultOptions(): array
    {
        return $this->config['defaults']['sms'] ?? [];
    }

}