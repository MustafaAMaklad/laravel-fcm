<?php

namespace Src\Providers;

use App\Factories\FirebaseAuthenticatorFactory;
use App\Notifications\FcmChannel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Src\Contracts\FcmServiceClient;
use Src\Contracts\ScopeAuthenticator;
use Src\Services\FcmService;

class FcmServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
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