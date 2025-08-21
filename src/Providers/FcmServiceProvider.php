<?php

namespace MustafaAMaklad\Fcm\Providers;

use MustafaAMaklad\Fcm\Factories\FirebaseAuthenticatorFactory;
use MustafaAMaklad\Fcm\Channels\FcmChannel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use MustafaAMaklad\Fcm\Contracts\FcmServiceClient;
use MustafaAMaklad\Fcm\Contracts\ScopeAuthenticator;
use MustafaAMaklad\Fcm\Services\FcmService;

class FcmServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/firebase.php',
            'firebase'
        );
        $this->app->bind(FirebaseAuthenticatorFactory::class);
        $this->app->singleton(ScopeAuthenticator::class, fn(Application $app): ScopeAuthenticator => $app->make(FirebaseAuthenticatorFactory::class)->create());
        $this->app->bind(FcmServiceClient::class, FcmService::class);
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Notification::extend('fcm', function ($app) {
            return new FcmChannel($app->make(FcmServiceClient::class));
        });
    }
}