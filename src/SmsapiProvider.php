<?php

namespace NotificationChannels\Smsapi;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\Smsapi\Exceptions\InvalidConfigException;
use Smsapi\Client\SmsapiHttpClient;

class SmsapiProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/smsapi-notification-channel.php', 'smsapi-notification-channel');

        $this->publishes([
            __DIR__ . '/../config/smsapi-notification-channel.php' => config_path('smsapi-notification-channel.php'),
        ]);

        $this->app->bind(SmsapiConfig::class, function () {
            return new SmsapiConfig($this->app['config']['smsapi-notification-channel']);
        });

        $this->app->singleton(SmsapiHttpClient::class, function (Application $app) {
            /** @var SmsapiConfig $config */
            $config = $app->make(SmsapiConfig::class);

            if ($config->usingComService()) {
                return (new SmsapiHttpClient())->smsapiComService($config->getAuthToken());
            }

            if ($config->usingPlService()) {
                return (new SmsapiHttpClient())->smsapiPlService($config->getAuthToken());
            }

            throw InvalidConfigException::missingConfig();
        });

        $this->app->singleton(Smsapi::class, function (Application $app) {
            return new Smsapi(
                $app->make(SmsapiHttpClient::class),
                $app->make(SmsapiConfig::class)
            );
        });

        $this->app->singleton(SmsapiChannel::class, function (Application $app) {
            return new SmsapiChannel(
                $app->make(Smsapi::class),
                $app->make(Dispatcher::class)
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            SmsapiConfig::class,
            SmsapiHttpClient::class,
            SmsapiChannel::class,
        ];
    }
}
