<?php

namespace MustafaAMaklad\Fcm\Contracts;

interface FcmMessageBuilder
{
    public function token(string $token): self;
    public function title(string $title): self;
    public function body(string $body): self;
    public function data(array $data): self;
    public function badge(int $count = 0): self;
    public function sound(string $sound = 'default'): self;
    public function apns(array $config = []): self;
    public function android(array $config = []): self;
    public function toArray(): array;
    public function toJson(): string;
}