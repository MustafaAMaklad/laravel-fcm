<?php

namespace MustafaAMaklad\Fcm\Contracts;

interface FcmServiceClient
{
    public function send(FcmMessageBuilder $fcmMessageBuilder): void;
}