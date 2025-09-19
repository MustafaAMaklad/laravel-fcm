<?php

namespace MustafaAMaklad\Fcm\Contracts;

interface FcmMessageBuilder
{
    public function token(string $token): self;
    public function title(string $title): self;
    public function body(string $body): self;
    public function type(string $type): self;
    public function id(string $id): self;
    public function badge(int $count = 1): self;
    public function sound(string $sound = 'default'): self;
    public function toArray(): array;
    public function toJson(): string;
}