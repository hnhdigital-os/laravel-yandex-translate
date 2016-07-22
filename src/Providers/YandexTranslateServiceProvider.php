<?php

namespace Bluora\Yandex\Providers;

use Bluora\Yandex\Translate;
use Illuminate\Support\ServiceProvider;

class YandexTranslateServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('YandexTranslate', function () {
            return new Translate();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
