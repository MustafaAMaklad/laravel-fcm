<?php

namespace Src\Contracts;

interface FcmServiceClient
{
    public function send(FcmMessageBuilder $fcmMessageBuilder): void;
}