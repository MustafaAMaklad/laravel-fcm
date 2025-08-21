<?php

namespace MustafaAMaklad\Fcm\Contracts;

interface FcmNotification
{
    public function toFcm(object $notifiable): FcmMessageBuilder;
}