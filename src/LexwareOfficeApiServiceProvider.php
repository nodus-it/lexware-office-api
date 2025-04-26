<?php

namespace Nodus\LexwareOfficeApi;

use Illuminate\Support\ServiceProvider;

class LexwareOfficeApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__.'/config/lexware-office.php' => config_path('lexware-office.php')], 'lexware-office');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/lexware-office.php', 'lexware-office');
    }
}
