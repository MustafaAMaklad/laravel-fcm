<?php

namespace Src\Contracts;

interface FcmNotification
{
    public function toFcm(object $notifiable): FcmMessageBuilder;
}