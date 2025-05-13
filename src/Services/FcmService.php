<?php

namespace Src\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Src\Contracts\ScopeAuthenticator;
use Src\Contracts\FcmMessageBuilder;
use Src\Exceptions\FcmSendingException;

class FcmService
{
    public function __construct(protected ScopeAuthenticator $scopeAuthenticator)
    {
    }

    public function send(FcmMessageBuilder $message): void
    {
        $response = Http::withHeader('Authorization', $this->scopeAuthenticator->getAccessToken(config('firebase.scopes.fcm')))
            ->asJson()
            ->post(config('firebase.messages.send'), $message->build());

        if ($response->failed()) {
            Log::error('FCM message failed', $response->json());
            throw new FcmSendingException($response->json('message'), $response->status());
        }
    }
}