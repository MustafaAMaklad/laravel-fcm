<?php

namespace MustafaAMaklad\Fcm\Services;

use MustafaAMaklad\Fcm\Contracts\FcmMessageBuilder;

class FcmMessage implements FcmMessageBuilder
{
    public const ANDROID_PRIORITY_HIGH = 'high';
    public const APNS_PRIORITY_IMMEDIATE = '10';
    public const APNS_CONTENT_AVAILABLE = 1;
    public const APNS_BADGE = 1;
    public const APNS_SOUND_DEFAULT = 'default';

    protected array $message = [
        'message' => [
            'token' => '',
            'data' => [
                'type' => '',
                'id' => '',
                'title' => '',
                'body' => '',
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => '',
                            'body' => '',
                        ],
                        'sound' => self::APNS_SOUND_DEFAULT,
                        'badge' => self::APNS_BADGE,
                        'content-available' => self::APNS_CONTENT_AVAILABLE,
                    ],
                    'type' => '',
                    'id' => '',
                ],
                'headers' => [
                    'apns-priority' => self::APNS_PRIORITY_IMMEDIATE,
                ],
            ],
            'android' => [
                'priority' => self::ANDROID_PRIORITY_HIGH,
            ],
        ]
    ];

    public function token(string $token): self
    {
        $this->message['message']['token'] = $token;

        return $this;
    }

    public function title(string $title): self
    {
        $this->message['message']['data']['title'] = $title;
        $this->message['message']['apns']['payload']['aps']['alert']['title'] = $title;

        return $this;
    }

    public function body(string $body): self
    {
        $this->message['message']['data']['body'] = $body;
        $this->message['message']['apns']['payload']['aps']['alert']['body'] = $body;
        return $this;
    }

    public function data(array $data): self
    {
        $this->message['message']['data'] = array_replace_recursive(
            $this->message['message']['data'],
            $data
        );

        return $this;
    }

    public function type(string $type): self
    {
        $this->message['message']['data']['type'] = $type;
        $this->message['message']['apns']['payload']['type'] = $type;

        return $this;
    }

    public function id(string $id): self
    {
        $this->message['message']['data']['id'] = $id;
        $this->message['message']['apns']['payload']['id'] = $id;

        return $this;
    }

    public function badge(int $count = 1): self
    {
        $this->message['message']['apns']['payload']['aps']['badge'] = $count;

        return $this;
    }

    public function sound(string $sound = 'default'): self
    {
        $this->message['message']['apns']['payload']['aps']['sound'] = $sound;

        return $this;
    }

    public function apns(array $config = []): self
    {
        if ($config === []) {
            $this->setDefaultApnsConfig();
        } else {
            $this->message['message']['apns'] = array_replace_recursive(
                $this->message['message']['apns'],
                $config
            );
        }

        return $this;
    }

    public function android(array $config = []): self
    {
        if ($config === []) {
            $this->setDefaultAndroidConfig();
        } else {
            $this->message['message']['android'] = array_replace_recursive(
                $this->message['message']['android'],
                $config
            );
        }

        return $this;
    }

    public function toArray(): array
    {
        return $this->message;
    }

    public function toJson(): string
    {
        return json_encode($this->message);
    }

    protected function setDefaultApnsConfig(): void
    {
        $this->message['message']['apns']['payload']['type'] = $this->message['message']['data']['type'];
        $this->message['message']['apns']['payload']['id'] = $this->message['message']['data']['id'];
        $this->message['message']['apns']['payload']['aps']['alert']['title'] = $this->message['message']['data']['title'];
        $this->message['message']['apns']['payload']['aps']['alert']['body'] = $this->message['message']['data']['body'];
    }

    protected function setDefaultAndroidConfig(): void
    {
        $this->message['message']['android']['priority'] = self::ANDROID_PRIORITY_HIGH;
    }
}