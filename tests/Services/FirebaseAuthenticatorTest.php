<?php

namespace MustafaAMaklad\Fcm\Tests\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use MustafaAMaklad\Fcm\Exceptions\FirebaseAuthorizationException;
use MustafaAMaklad\Fcm\Services\FirebaseAuthenticator;
use Orchestra\Testbench\TestCase;

class FirebaseAuthenticatorTest extends TestCase
{
    protected array $credentials;

    protected function setUp(): void
    {
        parent::setUp();

        $this->credentials = [
            'token_uri' => 'https://oauth2.googleapis.com/token',
            'client_email' => 'test@example.com',
            'private_key' => <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAvrE6KNHYoA1tW2CwZ0/0oL1V+fwSB6z9Pg6sGL6A7Hftt9fK
9B3Dz0VSwZV8qOjL+O7KvVY9W0sy1ITm0OefxQRO+JpgKMuR0jNGdmwIN2sfYkYl
D0nD8XJczHTOQpQ84yAac0Kw6d1dF4oFFfE9p2jMoOZ7rztT2X9gZHxd8oS1Gn0E
jZPSzOa9A+g8S8tu3srmZtWxIG3pRKu4pQoZpQZnG+mrVkM7qYFek5nfn2+2u0Be
n/1h8ssK8RQgBsyh56r7u8dxw/7Piv/Obr2r54keApQWNN6FwbDYfblp8PchD91N
06S2Wj5SV7TD4ps3fSycTbdv5a6b70QnCzCpqQIDAQABAoIBAQCPw1hj7iW5m5+k
Z9sLwOOBjcO0u5S3jzHzcpjhLz/XrYmUNBLg8gx+eJrMRCzXEDLMwCtHHzFGeT2W
6C5qZ3Cp6/X6TzXqriLW3ZRVjUvHpFYFvFxdkF0ZqOLtY4XH1dP04ySpuHTTbB6+
RkzhlcT6gda8mXcxGBXXpAcB5sZyXvGXbC5/mxXbAyke3hwNh1aOeYtlfStcc4rC
FwpmpE6HbqkLBNbY3m39f91cM2vJZGV1vCFrp2p2RL2A9QoKnkKeF7LPgEJ+HqTq
LLC8N3TlrAyD9aG9If7EyQwnMoFAy3RjV+icxEm1hG4F1nQ/CKN/MI/NO4cvHMPk
HJt6xqJBAoGBAPYpwtZTHHRhryLgQX2Jr3YpygtdjD8q+CvV8pGUEZBfrYGTVWBx
3lfk/4fTICQn6CrGOG3dD4hTUK2nAXej0RQatCErM1oVvfswDk4+SWx0m3eLzQq4
8/HT0X2oAc8WStvV3zUGMzF9e2a1vRmwl9f3WlDNhFOjVqlZUnb88QzPAoGBAMVu
y/ckcXqJDCv/0zS9BrEKwhtS6+1xMZqlIVrWpURjOwKh9U2C34d3Pj5SWRYXxVfD
5eN0GpH5r9Y2gwDnQ3RPGKN9Y0DC7TfxJjlXxtKnZv1PfMgKPCVrV3Q4BpjOqSXx
rD+ZSGmZSyf1Z5H9M6m+NLuPBfIMgoPJJ8Vud9cjAoGBAJrW9Wq/5XapXfF5n1d0
1DZ8j7y2eG7LoY6m6wVn07mC2yKZTT5JdHH2CsdVi9NNnUPQMPXWKiBap3pN71eH
zk9LVPrqQ7L6XpHgfnm/4yVfgc9YHT61BiwqUYoBSgOciRbZ64nGzZk0hYyUb9JU
MJQY7VLnrg7CMeWQ3v5AvUlBAoGAGXSUYV6qgm+Q2V6u17pvnU5EmJmF8Gx2IdI+
0DEHr7wDXIs8FPfZgG6dHjxFpmMDDpKhN6Zr5Z5mMZLgKuLz5XU9rNf7QUiDykCV
pOf+aaDGpuRn9Tgh5UXKgUNyUe2L3NE/HiN+clxEh4p0rZv1PNmK+vyrD08Xr9+w
N1xBQQ0CgYATgaUV5nFg8SxgTw8K9kXtIdcYrPsb1R/4kjH4eN8mD80fJk1wtQlM
lYxDj+OQHqCJ/b2bMqRXWxol2hPBM2d4NEmRx2h3M2KgHxQomDAfudCBkMY1szbb
RWk4hj4JpBgfnpQukOyyob1nbV9nXhwbMfS4ppP6VRmWRLgKZ13Qlg==
-----END RSA PRIVATE KEY-----
EOD
        ];
    }

    /** @test */
    public function it_fetches_and_caches_a_token()
    {
        Http::fake([
            'https://oauth2.googleapis.com/token' => Http::response([
                'access_token' => 'fake_token_123',
                'expires_in' => 3600,
            ], 200),
        ]);

        Cache::shouldReceive('has')->once()->andReturn(false);
        Cache::shouldReceive('put')->once();

        $authenticator = new FirebaseAuthenticator($this->credentials);
        $token = $authenticator->getAccessToken('test_scope');

        $this->assertEquals('fake_token_123', $token);
    }

    /** @test */
    public function it_returns_cached_token_if_exists()
    {
        Cache::shouldReceive('has')->once()->andReturn(true);
        Cache::shouldReceive('get')->once()->andReturn('cached_token');

        $authenticator = new FirebaseAuthenticator($this->credentials);
        $token = $authenticator->getAccessToken('test_scope');

        $this->assertEquals('cached_token', $token);
    }

    /** @test */
    public function it_throws_exception_if_firebase_returns_error()
    {
        Http::fake([
            'https://oauth2.googleapis.com/token' => Http::response([
                'message' => 'Invalid credentials'
            ], 400),
        ]);

        Log::shouldReceive('error')->once();

        $this->expectException(FirebaseAuthorizationException::class);

        $authenticator = new FirebaseAuthenticator($this->credentials);
        $authenticator->getAccessToken('test_scope');
    }
}

