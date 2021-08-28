<?php

namespace Azeroglu\Bot;

use Illuminate\Support\ServiceProvider;

class BotServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('Bot', function ($app) {
            return new Bot();
        });

        $this->app->alias('bot', Bot::class);
    }

    public function provides()
    {
        return ['bot' => Bot::class];
    }
}
