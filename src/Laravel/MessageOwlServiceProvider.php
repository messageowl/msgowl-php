<?php

namespace MessageOwl\Laravel;

use Illuminate\Support\ServiceProvider;
use MessageOwl\MessageOwl;

class MessageOwlServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/messageowl.php',
            'messageowl',
        );

        $this->app->singleton(MessageOwl::class, function ($app) {
            $config = $app['config']['messageowl'];

            return new MessageOwl(
                apiKey: $config['api_key'],
                timeout: (int) $config['timeout'],
                useQueryAuth: (bool) $config['use_query_auth'],
            );
        });

        $this->app->alias(MessageOwl::class, 'messageowl');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/messageowl.php' => config_path('messageowl.php'),
            ], 'messageowl-config');
        }
    }
}
