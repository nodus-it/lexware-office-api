<?php

namespace Tests;

use Dotenv\Dotenv;
use Nodus\LexwareOfficeApi\LexwareOfficeApiServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelData\LaravelDataServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelDataServiceProvider::class,
            LexwareOfficeApiServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->load();

        $app['config']->set('lexware-office.auth.token', env('LEXWARE_OFFICE_API_TOKEN'));;
    }
}
