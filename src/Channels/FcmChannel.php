<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Src\Contracts\FcmServiceClient;

class FcmChannel
{
    public function __construct(protected FcmServiceClient $fcmServiceClient)
    {
    }

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        /** @var \Src\Contracts\FcmNotification $notification */

        $message = $notification->toFcm($notifiable);

        $this->fcmServiceClient->send($message);
    }
}