<?php

namespace MustafaAMaklad\Fcm\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use MustafaAMaklad\Fcm\Providers\FcmServiceProvider;


abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            FcmServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Fcm' => \MustafaAMaklad\Fcm\Facades\Fcm::class,
        ];
    }
}
