<?php

namespace MustafaAMaklad\Fcm\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use MustafaAMaklad\Fcm\Contracts\ScopeAuthenticator;
use MustafaAMaklad\Fcm\Contracts\FcmMessageBuilder;
use MustafaAMaklad\Fcm\Contracts\FcmServiceClient;
use MustafaAMaklad\Fcm\Exceptions\FcmSendingException;

class FcmService implements FcmServiceClient
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