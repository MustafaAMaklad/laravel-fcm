<?php

namespace MustafaAMaklad\Fcm\Services;

use MustafaAMaklad\Fcm\Contracts\FcmMessageBuilder;

class FcmMessage implements FcmMessageBuilder
{
    protected array $message = [
        'message' => [
            'notification' => [
                'title' => '',
                'body' => '',
            ],
            'data' => [],
            'apns' => [],
            'android' => [],
        ]
    ];

    public function token(string $token): self
    {
        if (array_key_exists('topic', $this->message['message'])) {
            unset($this->message['message']['topic']);
        }

        $this->message['message']['token'] = $token;

        return $this;
    }

    public function title(string $title): self
    {
        $this->message['message']['notification']['title'] = $title;

        return $this;
    }

    public function body(string $body): self
    {
        $this->message['message']['notification']['body'] = $body;

        return $this;
    }
    
    public function data(array $data): self
    {
        $this->message['message']['data'] = $data;

        return $this;
    }

    public function badge(int $count): self
    {
        return $this;
    }

    public function sound(string $sound = 'default', bool $enabled = true): self
    {
        return $this;
    }

    public function apns(array $config = []): self
    {
        $this->message['message']['apns'] = $config;

        return $this;
    }

    public function android(array $config = []): self
    {
        $this->message['message']['android'] = $config;

        return $this;
    }

    public function build(bool $decoded = true): array|string
    {
        return $decoded ? $this->message : json_encode($this->message);
    }
}