<?php

namespace Nodus\LexwareOfficeApi;

use Illuminate\Support\ServiceProvider;
use Nodus\LexwareOfficeApi\Resources\LexwareOfficeResource;

class LexwareOfficeApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__.'/config/lexware-office.php' => config_path('lexware-office.php')], 'lexware-office');

        $this->app->singleton('lexware-office-api', function () {
            return new LexwareOfficeResource;
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/lexware-office.php', 'lexware-office');
    }
}
