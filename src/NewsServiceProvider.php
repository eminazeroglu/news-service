<?php

namespace Azeroglu\News;

use Illuminate\Support\ServiceProvider;

class NewsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('News', function ($app) {
            return new News();
        });

        $this->app->alias('news', News::class);
    }

    public function provides()
    {
        return ['news' => News::class];
    }
}
