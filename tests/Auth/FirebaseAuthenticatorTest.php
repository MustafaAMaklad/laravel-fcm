<?php

namespace MustafaAMaklad\Fcm\Tests\Auth;

use Illuminate\Support\Facades\Cache;
use MustafaAMaklad\Fcm\Services\FirebaseAuthenticator;
use Orchestra\Testbench\TestCase;

class FirebaseAuthenticatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_fetches_auth_token()
    {
        $authenticator = new FirebaseAuthenticator([
            'token_uri' => 'https://oauth2.googleapis.com/token',
            'client_id' => env('FIREBASE_CLIENT_ID'),
            'client_email' => env('FIREBASE_CLIENT_EMAIL'),
            'project_id' => env('FIREBASE_PROJECT_ID'),
            'private_key' => env('FIREBASE_PRIVATE_KEY'),
        ]);

        $token = $authenticator->getAccessToken('https://www.googleapis.com/auth/firebase.messaging');

        $this->assertNotEmpty($token);
    }

    /**
     * @test
     */
    public function it_caches_auth_token()
    {
        Cache::shouldReceive('has')
            ->once()
            ->andReturn(false);

        Cache::shouldReceive('put')
            ->once()
            ->andReturnNull();

        $authenticator = new FirebaseAuthenticator([
            'token_uri' => 'https://oauth2.googleapis.com/token',
            'client_id' => env('FIREBASE_CLIENT_ID'),
            'client_email' => env('FIREBASE_CLIENT_EMAIL'),
            'project_id' => env('FIREBASE_PROJECT_ID'),
            'private_key' => env('FIREBASE_PRIVATE_KEY'),
        ]);

        $token = $authenticator->getAccessToken('https://www.googleapis.com/auth/firebase.messaging');

        $this->assertNotEmpty($token);
    }
}

