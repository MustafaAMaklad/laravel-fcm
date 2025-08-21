<?php

namespace MustafaAMaklad\Fcm\Tests\Feature;

use MustafaAMaklad\Fcm\Tests\TestCase;
use MustafaAMaklad\Fcm\Contracts\FcmServiceClient;
use MustafaAMaklad\Fcm\Contracts\ScopeAuthenticator;
use MustafaAMaklad\Fcm\Services\FcmService;

class FcmServiceProviderTest extends TestCase
{
    /** @test */
    public function it_binds_fcm_service_client()
    {
        $client = $this->app->make(FcmServiceClient::class);

        $this->assertInstanceOf(FcmService::class, $client);
    }

    /** @test */
    public function it_binds_scope_authenticator_as_singleton()
    {
        $a = $this->app->make(ScopeAuthenticator::class);
        $b = $this->app->make(ScopeAuthenticator::class);

        $this->assertSame($a, $b); // must be singleton
    }

    /** @test */
    public function it_loads_config()
    {
        $this->assertIsArray(config('firebase'));
    }
}
