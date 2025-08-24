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

    protected string $androidCustomKey = '';
    protected bool $sync = true;

    protected array $message = [
        'message' => [
            'token' => '',
            'data' => [],
            'apns' => [],
            'android' => [],
        ]
    ];

    public function __construct()
    {
        $this->androidCustomKey = config('firebase.fcm.custom_android_key_name', 'notification_config');
    }

    public function sync(bool $sync = true): FcmMessageBuilder
    {
        $this->sync = $sync;

        return $this;
    }

    public function token(string $token): FcmMessageBuilder
    {
        $this->message['message']['token'] = $token;

        return $this;
    }

    public function title(string $title): FcmMessageBuilder
    {
        $this->message = $this->put($this->message, ['message', 'data', 'title'], $title);

        if ($this->sync) {
            $this->message = $this->put($this->message, ['message', 'apns', 'payload', 'aps', 'alert', 'title'], $title);
        }

        return $this;
    }

    public function body(string $body): FcmMessageBuilder
    {
        $this->message = $this->put($this->message, ['message', 'data', 'body'], $body);

        if ($this->sync) {
            $this->message = $this->put($this->message, ['message', 'apns', 'payload', 'aps', 'alert', 'body'], $body);
        }

        return $this;
    }

    public function type(string $type): FcmMessageBuilder
    {
        $this->message['message']['data']['type'] = $type;

        if ($this->sync) {
            $this->message['message']['apns']['payload']['type'] = $type;
        }

        return $this;
    }

    public function id(string $id): FcmMessageBuilder
    {
        $this->message['message']['data']['id'] = $id;

        if ($this->sync) {
            $this->message['message']['apns']['payload']['id'] = $id;
        }

        return $this;
    }

    public function badge(int $count = 1): FcmMessageBuilder
    {
        $this->message['message']['data'][$this->androidCustomKey]['badge'] = $count;

        if ($this->sync) {
            $this->message['message']['apns']['payload']['aps']['badge'] = $count;
        }

        return $this;
    }

    public function sound(string $sound = 'default'): FcmMessageBuilder
    {
        $this->message['message']['data'][$this->androidCustomKey]['sound'] = $sound;

        if ($this->sync) {
            $this->message['message']['apns']['payload']['aps']['sound'] = $sound;
        }

        return $this;
    }

    public function data(array $data): FcmMessageBuilder
    {
        $this->message['message']['data'] = array_replace_recursive(
            $this->message['message']['data'],
            $data
        );

        return $this;
    }

    public function apns(array $config = []): FcmMessageBuilder
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

    public function android(array $config = []): FcmMessageBuilder
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
        $this->message['message']['apns']['payload']['aps']['sound'] = $this->message['message']['data'][$this->androidCustomKey]['sound'] ?? self::APNS_SOUND_DEFAULT;
        $this->message['message']['apns']['payload']['aps']['badge'] = $this->message['message']['data'][$this->androidCustomKey]['badge'] ?? self::APNS_BADGE;
        $this->message['message']['apns']['priority'] = self::APNS_PRIORITY_IMMEDIATE;
    }

    protected function setDefaultAndroidConfig(): void
    {
        $this->message['message']['android']['priority'] = self::ANDROID_PRIORITY_HIGH;
    }

    protected function validate(): void
    {
        if (!isset($this->message['message']['token']) || $this->message['message']['token'] === '') {
            throw new \InvalidArgumentException('A token is required.');
        }

        $this->validateData();
        $this->validateApns();
    }

    protected function validateData(): void
    {
        if (!isset($this->message['message']['data']) || $this->message['message']['data'] === '') {
            throw new \InvalidArgumentException('A data is required.');
        }

        if (!isset($this->message['message']['data']['title']) || $this->message['message']['data']['title'] === '') {
            throw new \InvalidArgumentException('A title is required.');
        }

        if (!isset($this->message['message']['data']['body']) || $this->message['message']['data']['body'] === '') {
            throw new \InvalidArgumentException('A body is required.');
        }
    }

    protected function validateApns(): void
    {
        if (!isset($this->message['message']['apns']) || $this->message['message']['apns'] === []) {
            throw new \InvalidArgumentException('An apns is required.');
        }

        if (!isset($this->message['message']['apns']['payload']) || $this->message['message']['apns']['payload'] === []) {
            throw new \InvalidArgumentException('An apns payload is required.');
        }

        if (!isset($this->message['message']['apns']['payload']['aps']) || $this->message['message']['apns']['payload']['aps'] === []) {
            throw new \InvalidArgumentException('An apns payload aps is required.');
        }

        if (!isset($this->message['message']['apns']['payload']['aps']['alert']) || $this->message['message']['apns']['payload']['aps']['alert'] === []) {
            throw new \InvalidArgumentException('An apns payload aps alert is required.');
        }

        if (!isset($this->message['message']['apns']['payload']['aps']['alert']['title']) || $this->message['message']['apns']['payload']['aps']['alert']['title'] === '') {
            throw new \InvalidArgumentException('An apns payload aps alert title is required.');
        }

        if (!isset($this->message['message']['apns']['payload']['aps']['alert']['body']) || $this->message['message']['apns']['payload']['aps']['alert']['body'] === '') {
            throw new \InvalidArgumentException('An apns payload aps alert body is required.');
        }
    }

    public function put(array $root, array $path, mixed $value): array
    {
        if (empty($path)) {
            return $root;
        }

        $key = array_shift($path);

        if (empty($path)) {
            $newRoot = $root;
            $newRoot[$key] = $value;
            return $newRoot;
        }

        $newRoot = $root;

        $child = $newRoot[$key] ?? [];

        $newRoot[$key] = $this->put($child, $path, $value);

        return $newRoot;
    }

}