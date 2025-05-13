<?php

namespace Src\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Src\Contracts\ScopeAuthenticator;
use Src\Exceptions\FirebaseAuthorizationException;

class FirebaseAuthenticator implements ScopeAuthenticator
{
    public function __construct(
        protected array $credentials,
    ) {
    }

    protected function fetchAuthToken(string $scope): array
    {
        $response = Http::post($this->credentials['token_uri'], [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $this->generateJwt(
                [
                    'typ' => 'JWT',
                    'alg' => 'RS256'
                ],
                [
                    'iss' => $this->credentials['client_email'],
                    'scope' => $scope,
                    'aud' => $this->credentials['token_uri'],
                    'iat' => time(),
                    'exp' => time() + 3600,
                ],
                $this->credentials['private_key'],
            )
        ]);

        if ($response->failed()) {
            Log::error('Firebase authentication failed', $response->json());
            throw new FirebaseAuthorizationException($response->json('message'), $response->status());
        }

        return $response->json();
    }

    public function getAccessToken(string $scope): string
    {
        $key = $this->getCacheKey($scope);

        if (Cache::has($key)) {

            return Cache::get($key);
        }

        $token = $this->fetchAuthToken($scope);

        Cache::put($key, $token['access_token'], $token['expires_in']);

        return $token['access_token'];
    }

    protected function getCacheKey(string $scope): string
    {
        return "firebase_access_token:{$scope}"; 
    }

    public function generateJwt(array $header, array $payload, $private): string
    {
        $base64UrlHeader = base64_encode(json_encode($header));
        $base64UrlPayload = base64_encode(json_encode($payload));

        $signatureInput = "$base64UrlHeader.$base64UrlPayload";

        openssl_sign($signatureInput, $signature, $private, OPENSSL_ALGO_SHA256);

        $base64UrlSignature = base64_encode($signature);

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }
}